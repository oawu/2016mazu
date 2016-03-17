<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Cli2 extends Site_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }

  public function baishatun_com () {
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

    if (!verifyCreateOrm ($path = BaishatunComPath::create (array (
                'lat' => $result['lat'][0],
                'lng' => $result['lng'][0],
                'lat2' => $result['lat'][0] + (rand (-19999, 19999) * 0.00000001),
                'lng2' => $result['lng'][0] + (rand (-19999, 19999) * 0.00000001),
                'address' => '',
                'target' => '',
                'distance' => '',
                'time_at' => date ('Y-m-d H:i:s'),
              ))))
      return BaishatunErrorLog::create (array ('message' => '[baishatun com] 新增錯誤！'));
    return true;
  }
  public function clean_baishatun_cell () {
    clean_cell ('baishatun_cell', '*');
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

  public function clean_query () {
    $log = CrontabLog::start ('每 30 分鐘，清除 query logs');
    $this->load->helper ('file');
    write_file (FCPATH . 'application/logs/query.log', '', FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
    $log->finish ();
  }
// <CORSConfiguration>
//     <CORSRule>
//         <AllowedOrigin>*</AllowedOrigin>
//         <AllowedMethod>GET</AllowedMethod>
//         <MaxAgeSeconds>3000</MaxAgeSeconds>
//         <AllowedHeader>Authorization</AllowedHeader>
//     </CORSRule>
// </CORSConfiguration>
  // http://pic.mazu.ioa.tw/upload/baishatun/api.json
  
  public function error ($log, $msg = '不明原因錯誤！') {
      BaishatunErrorLog::create (array ('message' => $msg));
      $log->error ($msg);
      $this->mail (array (
          '錯誤原因' => $msg,
          '清除網址' => '<a href="' . base_url ('api', 'baishatun', 'clear') . '">點我</a>',
          '錯誤時間' => date ('Y-m-d H:i:s'),
        ));
      return true;
  }
  public function baishatun ($version = 25) {
    $log = CrontabLog::start ('每 1 分鐘更新');
    $path = FCPATH . 'temp/api.json';

    if (file_exists ($path))
      return $this->error ($log, '上一次還沒完成，或還沒清除檔案！');

    $r = render_cell ('baishatun_cell', 'api', 'BaishatunComPath', 0);
    $r = array (
        's' => true,
        'v' => $version,
        't' => date ('Y-m-d H:i:s'),
        'l' => $r['l'],
        'i' => $r['i'],
        'p' => $r['p'],
      );

    $this->load->helper ('file');
    if (!write_file ($path, json_encode ($r)))
      return $this->error ($log, '無罰寫入 json 檔案！');

    $this->baishatun_com ();
    $this->clean_baishatun_cell ();

    $r = render_cell ('baishatun_cell', 'api', 'BaishatunComPath', 0);
    $r = array (
        's' => true,
        'v' => $version,
        't' => date ('Y-m-d H:i:s'),
        'l' => $r['l'],
        'i' => $r['i'],
        'p' => $r['p'],
      );

    if (!write_file ($path, json_encode ($r)))
      return $this->error ($log, '無罰寫入 json 檔案！');

    $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');
    $this->load->library ('S3', Cfg::system ('s3', 'buckets', $bucket));
    if (!S3::putObjectFile ($path, $bucket, 'upload' . DIRECTORY_SEPARATOR . 'baishatun' . DIRECTORY_SEPARATOR . 'api.json', S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years')))))
      $this->error ($log, '丟到 S3 失敗！');

    $this->clean_baishatun_cell ();
    $log->finish ();

    return @unlink ($path) ? true : $this->error ($log, '刪除 json 失敗！');
  }
  public function baishatun_x () {
    $log = CrontabLog::start ('每 1 分鐘更新');
    $path = FCPATH . 'temp/hi.text';

    if (file_exists ($path))
      return $log->error ('上一次還沒完成！') && $this->mail (array (
          '錯誤原因' => '重複更新狀況！',
          '清除網址' => '<a href="' . base_url ('api', 'baishatun', 'clear') . '">點我</a>',
          '錯誤時間' => date ('Y-m-d H:i:s'),
        ));

    $this->load->helper ('file');
    write_file ($path, 'Hi!');

    $this->baishatun_com ();
    $this->clean_baishatun_cell ();

    $log->finish ();
    return @unlink ($path);
  }
}
