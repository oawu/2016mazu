<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Cli_march extends Site_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }

  private function _update_paths ($march) {
    $path = FCPATH . 'temp/march_' . $march->id . '_paths.json';
    $s3_path = 'api/march/' . $march->id . '/paths.json';

    if (file_exists ($path))
      return array ('march' => $march, 'msg' => '上一次還沒完成，或還沒清除檔案！');

    $this->load->helper ('file');
    if (!write_file ($path, json_encode (array ())))
      return array ('march' => $march, 'msg' => '寫入 json 檔案錯誤或失敗(1)！');

    $r = $this->_get_paths ($march);
    $r = array (
        's' => $r['s'],
        'v' => $march->version,
        't' => date ('Y-m-d H:i:s'),
        'l' => $r['l'],
        'c' => $march->icon,
        'i' => $r['i'],
        'p' => $r['p'],
      );

    if (!write_file ($path, json_encode ($r)))
      return array ('march' => $march, 'msg' => '寫入 json 檔案錯誤或失敗(2)！');

    $msg = array ();

    if (!$this->_put_s3 ($path, $s3_path))
      $msg = array ('march' => $march, 'msg' => '丟到 S3 失敗！');

    if (!@unlink ($path))
      $msg = array ('march' => $march, 'msg' => '刪除 json 失敗！');

    return $msg;
  }
  public function index () {
    $version = 1;

    $log = CrontabLog::start ('每 1 分鐘更新路線');
    $marches = March::find ('all', array ('conditions' => array ('is_enabled = 1')));

    foreach ($marches as $march)
      if ($re = $this->_update_paths ($march))
        $this->_error ($log, $re['march']->title . '(' . $re['march']->id . ')' . $re['msg']);

    return $log->finish ();
  }

  private function _put_s3 ($path, $s3_path) {
    $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');
    $this->load->library ('S3', Cfg::system ('s3', 'buckets', $bucket));
    return S3::putObjectFile ($path, $bucket, $s3_path, S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years'))));
  }

  private function _get_paths ($march) {
    $is_ios = $march->is_ios;
    
    $first = MarchPath::first (array ('select' => 'id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id,  $is_ios)));
    $last = MarchPath::last (array ('select' => 'id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id,  $is_ios)));

    $point_ids = array ();
    if (!($all_ids = column_array (MarchPath::find ('all', array ('select' => 'id', 'order' => 'id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id, $is_ios))), 'id')))
      return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());

    $c = count ($all_ids);
    $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.01 : 0.05 : 0.15 : 0.3 : 0.46 : 1 : 1.5 : 2.3 : 3;
    for ($i = 0; ($key = round (($i * (2 + ($i - 1) * $unit)) / 2)) < $all_ids[0]; $i++)
      if ($temp = array_slice ($all_ids, $key, 1))
        array_push ($point_ids, array_shift ($temp));
    if (!$point_ids) return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());

    $paths = MarchPath::find ('all', array ('select' => 'id,latitude2,longitude2,time_at', 'order' => 'id DESC', 'conditions' => array ('id IN (?) AND march_id = ? AND is_enabled = 1 AND is_ios = ?', $point_ids, $march->id, $is_ios)));
    // $paths = MarchPath::find ('all', array ('select' => 'id,latitude2,longitude2,time_at', 'order' => 'id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id, $is_ios)));

    if ($paths[0]->id != $last->id) array_unshift ($paths, $last);
    if ($paths[count ($paths) - 1]->id != $first->id) array_push ($paths, $first);

    $paths = array_map (function ($path) {
      return array (
            'i' => $path->id,
            'a' => $path->latitude2,
            'n' => $path->longitude2,
            't' => $path->time_at->format ('Y-m-d H:i:s')
          );
    }, $paths);
    
    $paths = array_reverse ($paths);
    $paths = array_splice ($paths, 0);

    $this->load->library ('SphericalGeometry');
    $l = round (SphericalGeometry::computeLength (array_map (function ($path) {return new LatLng ($path['a'], $path['n']);}, $paths)) / 1000, 2);

    // $is = array_map (function ($i) {
    //   return array (
    //       'm' => $i->msgs (),
    //       'a' => $i->lat,
    //       'n' => $i->lng,
    //     );
    // }, BaishatunPathInfo::all ());

    return array (
        's' => true,
        'p' => $paths,
        'l' => $l,
        'i' => array ()
      );
  }

  private function _error ($log, $msg = '不明原因錯誤！', $url = '') {
      $log->error ($msg);
      // $this->mail ($url ? array (
      //     '錯誤原因' => $msg,
      //     '清除網址' => '<a href="' . $url . '">點我</a>',
      //     '錯誤時間' => date ('Y-m-d H:i:s'),
      //   ) : array (
      //     '錯誤原因' => $msg,
      //     '錯誤時間' => date ('Y-m-d H:i:s'),
      //   ));
      return true;
  }
}
