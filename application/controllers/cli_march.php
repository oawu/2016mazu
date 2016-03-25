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

  public function index ($march_id = 1) {
    $version = 1;
    $log = CrontabLog::start ('每 1 分鐘更新路線');

    $path = FCPATH . 'temp/march_' . $march_id . '_paths.json';
    $s3_path = 'api/march/' . $march_id . '/paths.json';

    if (file_exists ($path))
      return $this->_error ($log, '上一次還沒完成，或還沒清除檔案！', base_url ('api', 'baishatun', 'clear_api'));

    $this->load->helper ('file');
    if (!write_file ($path, json_encode (array ())))
      return $this->_error ($log, '寫入 json 檔案錯誤或失敗！');

    $r = $this->_get_paths ($march_id);
    $r = array (
        's' => $r['s'],
        'v' => $version,
        't' => date ('Y-m-d H:i:s'),
        'l' => $r['l'],
        'i' => $r['i'],
        'p' => $r['p'],
      );

    if (!write_file ($path, json_encode ($r)))
      return $this->_error ($log, '寫入 json 檔案錯誤或失敗！');

    if (!$this->_put_s3 ($path, $s3_path))
      $this->_error ($log, '丟到 S3 失敗！');

    $log->finish ();
    return @unlink ($path) ? true : $this->_error ($log, '刪除 json 失敗！', base_url ('api', 'baishatun', 'clear_api'));
  }

  private function _put_s3 ($path, $s3_path) {
    $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');
    $this->load->library ('S3', Cfg::system ('s3', 'buckets', $bucket));
    return S3::putObjectFile ($path, $bucket, $s3_path, S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years'))));
  }

  private function _get_paths ($march_id) {
    if (!($march_id && ($march = March::find_by_id ($march_id))))
      return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());

    $first = MarchPath::first (array ('select' => 'sqlite_id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1', $march->id)));
    $last = MarchPath::last (array ('select' => 'sqlite_id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1', $march->id)));

    $point_ids = array ();
    if (!($all_point_ids = column_array (MarchPath::find ('all', array ('select' => 'sqlite_id', 'order' => 'sqlite_id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND accuracy_horizontal < 50', $march->id))), 'sqlite_id')))
      return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());

    $c = count ($all_point_ids);
    $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.01 : 0.05 : 0.15 : 0.3 : 0.46 : 1 : 1.5 : 2.3 : 3;
    for ($i = 0; ($key = round (($i * (2 + ($i - 1) * $unit)) / 2)) < $all_point_ids[0]; $i++)
      if ($temp = array_slice ($all_point_ids, $key, 1))
        array_push ($point_ids, array_shift ($temp));
    if (!$point_ids) return $point_ids;

    $paths = MarchPath::find ('all', array ('select' => 'sqlite_id,latitude2,longitude2,time_at', 'order' => 'id DESC', 'conditions' => array ('sqlite_id IN (?) AND march_id = ? AND is_enabled = 1', $point_ids, $march->id)));

    if ($paths[0]->sqlite_id != $last->sqlite_id) array_unshift ($paths, $last);
    if ($paths[count ($paths) - 1]->sqlite_id != $first->sqlite_id) array_push ($paths, $first);

    $paths = array_map (function ($path) {
      return array (
            'i' => $path->sqlite_id,
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
      $this->mail ($url ? array (
          '錯誤原因' => $msg,
          '清除網址' => '<a href="' . $url . '">點我</a>',
          '錯誤時間' => date ('Y-m-d H:i:s'),
        ) : array (
          '錯誤原因' => $msg,
          '錯誤時間' => date ('Y-m-d H:i:s'),
        ));
      return true;
  }
}
