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
  public function index () {
    $log = CrontabLog::start ('每 1 分鐘更新路線');
    
    $version = 1;

    $path = FCPATH . 'temp/march_gps.json';
    $s3_path = 'api/march/gps.json';

    if (file_exists ($path))
      return $this->_error ($log, 'GPS 上一次還沒完成，或還沒清除檔案！');

    if (!write_file ($path, json_encode (array ())))
      return $this->_error ($log, 'GPS 寫入 json 檔案錯誤或失敗(1)！');

    $data = array (
      'v' => $version,
      'm' => array_map (function ($march) {
          $p = $march->paths2 ();
          return array (
              'i' => $march->id,
              'n' => $march->title,
              't' => $p['t'],
              'p' => $p['p']
            );
        }, March::find ('all', array ('select' => 'id,title,is_ios', 'conditions' => array ('is_enabled = 1')))));


    if (!write_file ($path, json_encode ($data)))
      return $this->_error ($log, 'GPS 寫入 json 檔案錯誤或失敗(2)！');

    if (!put_s3 ($path, $s3_path))
      return $this->_error ($log, 'GPS 丟到 S3 失敗！');

    if (!@unlink ($path))
      return $this->_error ($log, 'GPS 刪除 json 失敗！');

    return $log->finish ();
  }

  // private function _o ($paths) {
  //   if (!$paths) return $paths;
  //   $url = 'https://roads.googleapis.com/v1/snapToRoads';
  //   $url .= '?' . http_build_query (array ('key' => Cfg::setting ('google', ENVIRONMENT, 'server_key'), 'interpolate' => true, 'path' => implode ('|', array_map (function ($path) {
  //         return $path['a'] . ',' . $path['n'];
  //       }, $paths))));

  //   $res = file_get_contents ($url);
  //   $res = json_decode ($res, true);
  //   if (!(isset ($res['snappedPoints']) && is_array ($res['snappedPoints']) && $res['snappedPoints']))
  //     return $paths;

  //   foreach ($res['snappedPoints'] as $i => $point) {
  //     if (!(isset ($point['location']['latitude']) && isset ($point['location']['longitude'])))
  //       continue;

  //     if (isset ($paths[$i])) {
  //       $paths[$i]['a'] = $point['location']['latitude'];
  //       $paths[$i]['n'] = $point['location']['longitude'];
  //     } else {
  //       array_push ($paths, array_merge ($paths[count ($paths) - 1], array (
  //           'a' => $point['location']['latitude'],
  //           'n' => $point['location']['longitude'],
  //         )));
  //     }
  //   }

  //   return $paths;
  // }
  // private function _update_paths ($march) {
  //   $path = FCPATH . 'temp/march_' . $march->id . '_paths.json';
  //   $s3_path = 'api/march/' . $march->id . '/paths.json';

  //   if (file_exists ($path))
  //     return array ('march' => $march, 'msg' => '上一次還沒完成，或還沒清除檔案！');

  //   if (!write_file ($path, json_encode (array ())))
  //     return array ('march' => $march, 'msg' => '寫入 json 檔案錯誤或失敗(1)！');

  //   $r = $march->paths ();
  //   // $r['p'] = $this->_o ($r['p']);

  //   $r = array (
  //       's' => $r['s'],
  //       'v' => $march->version,
  //       't' => date ('Y-m-d H:i:s'),
  //       'l' => $r['l'],
  //       'c' => $march->icon,
  //       'i' => $r['i'],
  //       'p' => $r['p'],
  //     );

  //   if (!write_file ($path, json_encode ($r)))
  //     return array ('march' => $march, 'msg' => '寫入 json 檔案錯誤或失敗(2)！');

  //   $msg = array ();

  //   if (!put_s3 ($path, $s3_path))
  //     $msg = array ('march' => $march, 'msg' => '丟到 S3 失敗！');

  //   if (!@unlink ($path))
  //     $msg = array ('march' => $march, 'msg' => '刪除 json 失敗！');

  //   return $msg;
  // }
  // public function index () {
  //   $version = 1;

  //   $log = CrontabLog::start ('每 1 分鐘更新路線');
  //   $marches = March::find ('all', array ('conditions' => array ('is_enabled = 1')));

  //   foreach ($marches as $march)
  //     if ($re = $this->_update_paths ($march))
  //       $this->_error ($log, $re['march']->title . '(' . $re['march']->id . ')' . $re['msg']);

  //   return $log->finish ();
  // }


  // private function _get_paths ($march) {
  //   $is_ios = $march->is_ios;
    
  //   $first = MarchPath::first (array ('select' => 'id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id,  $is_ios)));
  //   $last = MarchPath::last (array ('select' => 'id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id,  $is_ios)));

  //   $point_ids = array ();
  //   if (!($all_ids = column_array (MarchPath::find ('all', array ('select' => 'id', 'order' => 'id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id, $is_ios))), 'id')))
  //     return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());

  //   $c = count ($all_ids);
  //   $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.01 : 0.05 : 0.15 : 0.3 : 0.46 : 1 : 1.5 : 2.3 : 3;
  //   for ($i = 0; ($key = round (($i * (2 + ($i - 1) * $unit)) / 2)) < $all_ids[0]; $i++)
  //     if ($temp = array_slice ($all_ids, $key, 1))
  //       array_push ($point_ids, array_shift ($temp));
  //   if (!$point_ids) return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());

  //   $paths = MarchPath::find ('all', array ('select' => 'id,latitude2,longitude2,time_at', 'order' => 'id DESC', 'conditions' => array ('id IN (?) AND march_id = ? AND is_enabled = 1 AND is_ios = ?', $point_ids, $march->id, $is_ios)));
  //   // $paths = MarchPath::find ('all', array ('select' => 'id,latitude2,longitude2,time_at', 'order' => 'id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $march->id, $is_ios)));

  //   if ($paths[0]->id != $last->id) array_unshift ($paths, $last);
  //   if ($paths[count ($paths) - 1]->id != $first->id) array_push ($paths, $first);

  //   $paths = array_map (function ($path) {
  //     return array (
  //           'i' => $path->id,
  //           'a' => $path->latitude2,
  //           'n' => $path->longitude2,
  //           't' => $path->time_at->format ('Y-m-d H:i:s')
  //         );
  //   }, $paths);
    
  //   $paths = array_reverse ($paths);
  //   $paths = array_splice ($paths, 0);

  //   $this->load->library ('SphericalGeometry');
  //   $l = round (SphericalGeometry::computeLength (array_map (function ($path) {return new LatLng ($path['a'], $path['n']);}, $paths)) / 1000, 2);

  //   $is = array_map (function ($i) {
  //     return array (
  //         'm' => $i->msgs (),
  //         'a' => $i->latitude,
  //         'n' => $i->longitude,
  //       );
  //   }, MarchInfo::find ('all', array ('select' => 'msgs,latitude,longitude','conditions' => array ('march_id = ?', $march->id))));

  //   return array (
  //       's' => true,
  //       'p' => $paths,
  //       'l' => $l,
  //       'i' => array ()
  //     );
  // }

  // public function heatmap () {
  //   $log = CrontabLog::start ('每 5 分鐘更新熱點');
  //   $qs = array (0, 1, 2, 3, 4);
    
  //   foreach ($qs as $q) {
  //     $path = FCPATH . 'temp/heatmap_' . $q . '.json';
  //     $s3_path = 'api/heatmap/' . $q . '.json';
      
  //     if (file_exists ($path))
  //       return $this->_error ($log, '上次 heatmap 尚未清除，q: ' . $q);

  //     if (!write_file ($path, json_encode (array ())))
  //       return $this->_error ($log, '寫入 json 檔案錯誤或失敗(1)！q: ' . $q);

  //     $heatmaps = $this->_get_heatmaps ($q);
      
  //     if (!write_file ($path, json_encode ($heatmaps)))
  //       return $this->_error ($log, '寫入 json 檔案錯誤或失敗(2)！q: ' . $q);

  //     if (!put_s3 ($path, $s3_path))
  //         return $this->_error ($log, '丟到 S3 失敗！');
      
  //     if (!@unlink ($path))
  //         return $this->_error ($log, '刪除 Temp 失敗！');
  //   }
    
  //   return $log->finish ();
  // }
  // public function _get_heatmaps ($q = 0) {
  //   $unit = 60; //sec

  //   $end = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * $q) . ' minutes'));
  //   $start = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * ($q + 1)) . ' minutes'));

  //   $users = MarchUser::find ('all', array ('select' => 'latitude,longitude', 'conditions' => array ('created_at BETWEEN ? AND ?', $start, $end)));

  //   $temp = null;
  //   $qs = array ();

  //   foreach ($users as $user)
  //     if (!$temp || ($temp->latitude != $user->latitude) || ($temp->longitude != $user->longitude))
  //       if ($temp = $user)
  //         array_push ($qs, array ('a' => $temp->latitude, 'n' => $temp->longitude));

  //   $qs = count ($qs) < 400 ? 
  //           count ($qs) < 200 ? 
  //             count ($qs) < 100 ? 
  //               count ($qs) < 50 ? 
  //                 array_merge ($qs, array_map ('rand_x', $qs), array_map ('rand_x', $qs), array_map ('rand_x', $qs), array_map ('rand_x', $qs)) : 
  //                 array_merge ($qs, array_map ('rand_x', $qs), array_map ('rand_x', $qs), array_map ('rand_x', $qs)) : 
  //                 array_merge ($qs, array_map ('rand_x', $qs), array_map ('rand_x', $qs)) : 
  //                 array_merge ($qs, array_map ('rand_x', $qs)) :
  //                 array_merge ($qs)
  //                 ;

  //   array_rand ($qs);

  //   return $qs;
  // }
  private function _error ($log, $msg = '不明原因錯誤！', $url = '') {
    $log->error ($msg);
    Mail::send ('錯誤', array (
      'OA <comdan66@gmail.com>',
    ), array (
      '訊息內容' => $msg,
    ));
    return true;
  }
}
