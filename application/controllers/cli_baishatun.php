<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Cli_baishatun extends Site_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }


// <CORSConfiguration>
//     <CORSRule>
//         <AllowedOrigin>*</AllowedOrigin>
//         <AllowedMethod>GET</AllowedMethod>
//         <MaxAgeSeconds>3000</MaxAgeSeconds>
//         <AllowedHeader>Authorization</AllowedHeader>
//     </CORSRule>
// </CORSConfiguration>
  public function clean_query () {
    $log = CrontabLog::start ('每 30 分鐘，清除 query logs');
    write_file (FCPATH . 'application/logs/query.log', '', FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
    $log->finish ();
  }
  public function index ($version = 49) {
    $log = CrontabLog::start ('每 1 分鐘更新路線');

    $path = FCPATH . 'temp/api.json';
    $s3_path = 'upload/baishatun/api.json';

    if (file_exists ($path))
      return $this->_error ($log, '上一次還沒完成，或還沒清除檔案！', base_url ('api', 'baishatun', 'clear_api'));

    if (!write_file ($path, json_encode (array ())))
      return $this->_error ($log, '寫入 json 檔案錯誤或失敗！');

    $this->_get_gps_info ();
    $r = $this->_get_paths ();
    $r = array (
        's' => true,
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
  private function _get_paths () {
    $m_id = 0;
    $first = BaishatunPath::first (array ('select' => 'id,lat,lng,lat2,lng2,time_at', 'conditions' => array ('is_enabled = 1 AND id > ?', $m_id)));
    $last = BaishatunPath::last (array ('select' => 'id,lat,lng,lat2,lng2,time_at', 'conditions' => array ('is_enabled = 1 AND id > ?', $m_id)));

    $point_ids = array ();
    if (!($all_point_ids = column_array (BaishatunPath::find ('all', array ('select' => 'id', 'order' => 'id DESC', 'conditions' => array ('is_enabled = 1 AND id > ?', $m_id))), 'id')))
      return $point_ids;

    $c = count ($all_point_ids);
    $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.01 : 0.05 : 0.15 : 0.3 : 0.46 : 1 : 1.5 : 2.3 : 3;
    for ($i = 0; ($key = round (($i * (2 + ($i - 1) * $unit)) / 2)) < $all_point_ids[0]; $i++)
      if ($temp = array_slice ($all_point_ids, $key, 1))
        array_push ($point_ids, array_shift ($temp));
    if (!$point_ids) return $point_ids;

    $paths = BaishatunPath::find ('all', array ('select' => 'id,lat,lng,lat2,lng2,time_at', 'order' => 'id DESC', 'conditions' => array ('id IN (?) AND is_enabled = 1 AND id > ?', $point_ids, $m_id)));
    if ($paths[0]->id != $last->id) array_unshift ($paths, $last);
    if ($paths[count ($paths) - 1]->id != $first->id) array_push ($paths, $first);

    $paths = array_map (function ($path) {
      return array (
            'i' => $path->id,
            'a' => isset ($path->lat2) && ($path->lat2 != '') ? $path->lat2 : $path->lat,
            'n' => isset ($path->lng2) && ($path->lng2 != '') ? $path->lng2 : $path->lng,
            't' => $path->time_at->format ('Y-m-d H:i:s')
          );
    }, $paths);
    
    $paths = array_reverse ($paths);
    $paths = array_splice ($paths, 0);

    $this->load->library ('SphericalGeometry');
    $l = round (SphericalGeometry::computeLength (array_map (function ($path) {return new LatLng ($path['a'], $path['n']);}, $paths)) / 1000, 2);
    
    $is = array_map (function ($i) {
      return array (
          'm' => $i->msgs (),
          'a' => $i->lat,
          'n' => $i->lng,
        );
    }, BaishatunPathInfo::all ());

    return array (
        'p' => $paths,
        'l' => $l,
        'i' => $is
      );
  }
  private function _get_gps_info () {
    $this->load->library ('phpQuery');
    $url = 'http://i.bamboocat.net/gps/';

    if (!($get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url))))) {
      BaishatunErrorLog::create (array ('message' => '[baishatun com] 取不到原始碼！'));
      return false; 
    }

    preg_match_all ('/addMarker\s*\((?P<lat>.*)\s*,\s*(?P<lng>.*)\);/', $get_html_str, $result);
    if (!($result['lat'] && $result['lng']&& $result['lat'][0] && $result['lng'][0])) {
      BaishatunErrorLog::create (array ('message' => '[baishatun com] 網頁內容有誤！'));
      return false; 
    }

    if (!verifyCreateOrm ($path = BaishatunPath::create (array (
                'lat' => $result['lat'][0],
                'lng' => $result['lng'][0],
                'lat2' => $result['lat'][0] + (rand (-29999, 29999) * 0.00000001),
                'lng2' => $result['lng'][0] + (rand (-29999, 29999) * 0.00000001),
                'address' => '',
                'target' => '',
                'distance' => '',
                'time_at' => date ('Y-m-d H:i:s'),
              ))))
      return BaishatunErrorLog::create (array ('message' => '[baishatun com] 新增錯誤！'));
    return true;
  }

  public function mail ($msgs = array ()) {
    if (!$msgs)
      $msgs = array (
          '錯誤原因' => '不明原因錯誤！',
          '錯誤時間' => date ('Y-m-d H:i:s'),
        );
    
    $html = "<article style='font-size:15px;line-height:22px;color:rgb(85,85,85)'><p style='margin-bottom:0'>Hi 管理員,</p><section style='padding:5px 20px'><p>剛剛發生了系統異常的狀況，以下是錯誤訊息：</p><table style='width:100%;border-collapse:collapse'><tbody>";
    foreach ($msgs as $title => $msg)
      $html .= "<tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>" . $title . "：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $msg . "</td></tr>";
    $html .= "</tbody></table><br/><p style='text-align:right'>如果需要詳細列表，可以置<a href='" . base_url ('admin') . "' style='color:rgba(96,156,255,1);margin:0 2px'>管理後台</a>檢閱。</p></section></article>";
    
    $this->load->library ('OaMailGun');
    $mail = new OaMailGun ();
    $result = $mail->sendMessage (array (
              'from' => Cfg::setting ('mail_gun', 'user', 'system', 'name') . ' <' . Cfg::setting ('mail_gun', 'user', 'system', 'email') . '>',
              'to' => 'OA' . ' <comdan66@gmail.com>',
              'subject' => '[排程錯誤] ' . Cfg::setting ('mail_gun', 'user', 'system', 'subject'),
              'html' => $html
            ));
  }
  private function _error ($log, $msg = '不明原因錯誤！', $url = '') {
      BaishatunErrorLog::create (array ('message' => $msg));
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

  public function heatmap () {
    $log = CrontabLog::start ('每 5 分鐘更新熱點');
    $qs = array (0, 1, 2, 3, 4);
    $path = FCPATH . "temp/heatmap_%d.json";
    $s3_path = "upload/baishatun/heatmap_%d.json";

    $datas = array_map (function ($i) use ($path, $s3_path) { return array ('q' => $i, 'p' => sprintf ($path, $i), 's' => sprintf ($s3_path, $i)); }, $qs);
    $datas = array_filter ($datas, function ($data) { return !file_exists ($data['p']); });

    if (count ($datas) != count ($qs)) return $this->_error ($log, '上一次還沒完成，或還沒清除檔案！', base_url ('api', 'baishatun', 'clear_heatmaps'));

    $ws = array_filter ($datas, function ($data) { return write_file ($data['p'], json_encode (array ())); });

    if (count ($ws) != count ($datas))
      return $this->_error ($log, '寫入 json 檔案錯誤或失敗！', base_url ('api', 'baishatun', 'clear_heatmaps'));

    $that = $this;
    $datas = array_map (function ($data) use ($that) {
      return array_merge ($data, array ('d' => array ('s' => true, 'q' => $that->_get_heatmaps ($data['q']))));
    }, $datas);

    $ws = array_filter ($datas, function ($data) {
      return write_file ($data['p'], json_encode ($data['d']));
    });

    if (count ($ws) != count ($datas))
      return $this->_error ($log, '寫入 json 檔案錯誤或失敗！', base_url ('api', 'baishatun', 'clear_heatmaps'));
    
    $that = $this;
    $ws = array_filter ($datas, function ($data) use ($that) {
      return $that->_put_s3 ($data['p'], $data['s']);
    });

    if (count ($ws) != count ($datas))
      $this->_error ($log, '丟到 S3 失敗！');
    
    $log->finish ();
    $ws = array_filter ($datas, function ($data) {
      return @unlink ($data['p']);
    });

    return (count ($ws) == count ($datas)) ? true : $this->_error ($log, '刪除 json 失敗！', base_url ('api', 'baishatun', 'clear_heatmaps'));
  }
  public function _get_heatmaps ($q = 0) {
    $unit = 60; //sec

    $end = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * $q) . ' minutes'));
    $start = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * ($q + 1)) . ' minutes'));

    $users = BaishatunUser::find ('all', array ('select' => 'lat,lng', 'conditions' => array ('created_at BETWEEN ? AND ?', $start, $end)));

    $temp = null;
    $qs = array ();

    foreach ($users as $user)
      if (!$temp || ($temp->lat != $user->lat) || ($temp->lng != $user->lng))
        if ($temp = $user)
          array_push ($qs, array ('a' => $temp->lat, 'n' => $temp->lng));

    $qs = count ($qs) < 400 ? 
            count ($qs) < 200 ? 
              count ($qs) < 100 ? 
                count ($qs) < 50 ? 
                  array_merge ($qs, array_map ('rand_x', $qs), array_map ('rand_x', $qs), array_map ('rand_x', $qs), array_map ('rand_x', $qs)) : 
                  array_merge ($qs, array_map ('rand_x', $qs), array_map ('rand_x', $qs), array_map ('rand_x', $qs)) : 
                  array_merge ($qs, array_map ('rand_x', $qs), array_map ('rand_x', $qs)) : 
                  array_merge ($qs, array_map ('rand_x', $qs)) :
                  array_merge ($qs)
                  ;

    array_rand ($qs);

    return $qs;
  }
}
