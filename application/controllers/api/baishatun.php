<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Baishatun extends Api_controller {
  private $version = 1;
  public function __construct () {
    parent::__construct ();
    header ('Content-type: text/html');

    if (ENVIRONMENT == 'production')
      header ('Access-Control-Allow-Origin: http://comdan66.github.io');
    else
      header ('Access-Control-Allow-Origin: *');

    $this->version = 25;
  }

  public function com ($id = 0) {
    $r = render_cell ('baishatun_cell', 'api', 'BaishatunComPath', $id);
    return $this->output_json (array (
        'v' => $this->version, 's' => true, 'p' => $r['p'], 'l' => $r['l'], 'i' => $r['i']
      ));
  }

  public function showtaiwan1 ($id = 0) {
    $r = render_cell ('baishatun_cell', 'api', 'BaishatunShowtaiwan1Path', $id);
    return $this->output_json (array (
        'v' => $this->version, 's' => true, 'p' => $r['p'], 'l' => $r['l'], 'i' => $r['i']
      ));
  }

  public function showtaiwan2 ($id = 0) {
    $r = render_cell ('baishatun_cell', 'api', 'BaishatunShowtaiwan2Path', $id);
    return $this->output_json (array (
        'v' => $this->version, 's' => true, 'p' => $r['p'], 'l' => $r['l'], 'i' => $r['i']
      ));
  }

  public function heatmap ($q = 0) {
    $q = $q < 0 ? 0 : ($q > 5 ? 4 : $q);
    $q = render_cell ('baishatun_cell', 'heatmap', $q);
    return $this->output_json (array (
        's' => true, 'q' => $q
      ));
  }
  public function location () {
    $posts = OAInput::post ();
    if (!(isset ($posts['a']) && isset ($posts['n']) && ($a = trim ($posts['a'])) && ($n = trim ($posts['n'])))) {
      BaishatunErrorLog::create (array ('message' => '[location] POST 錯誤！'));
      return;
    }
    
    $ip = $this->input->ip_address ();

    if (!verifyCreateOrm (BaishatunUser::create (array (
                'ip'  => isset ($ip) && $ip ? $ip : '0.0.0.0',
                'lat' => $a,
                'lng' => $n,
              ))))
      BaishatunErrorLog::create (array ('message' => '[location] 新增錯誤！'));
  }
  public function clear_api () {
    echo $path = FCPATH . 'temp/api.json';
    echo ' ...... ';
    @unlink ($path);
    echo !file_exists ($path) ? 'OK' : 'NO';
  }
  public function clear_heatmaps () {
    $paths = array ();

    for ($i = 0; $i < 10; $i++) {
      echo '<div style="margin:5px;">';
      echo $path = FCPATH . 'temp/heatmap' . $i . '.json';
      echo ' ...... ';
      @unlink ($path);
      echo !file_exists ($path) ? 'OK' : 'NO';
      echo '</div>';
    }
  }

  private function _put_mag () {
    $bl = column_array (BaishatunBlacklist::find ('all', array ('select' => 'ip')), 'ip');
    $path = FCPATH . 'temp/put_msgs_to_s3.text';

    if (file_exists ($path))
      return ;

    $this->load->helper ('file');
    if (!write_file ($path, json_encode (array ())))
      return ;

    $msgs = array_map (function ($msg) {
      return array (
          'a' => $msg->user_id ? true : false,
          'i' =>$msg->ip,
          'm' => $msg->message,
          't' => $msg->created_at->format ('Y-m-d H:i:s')
        );
    }, BaishatunMessage::find ('all', array (
        'select' => 'ip, user_id, message, created_at',
        'limit' => 40,
        'order' => 'id DESC',
        'conditions' => $bl ? array ('ip NOT IN (?)', $bl) : array ()
      )));

    if (!write_file ($path, json_encode (array (
        's' => true,
        't' => date ('Y-m-d H:i:s'),
        'm' => $msgs
      ))))
      return @unlink ($path);


    $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');
    $this->load->library ('S3', Cfg::system ('s3', 'buckets', $bucket));
    S3::putObjectFile ($path, $bucket, 'upload' . DIRECTORY_SEPARATOR . 'baishatun' . DIRECTORY_SEPARATOR . 'mags.json', S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years'))));

    return @unlink ($path);
  }
  public function ip () {
    if (!(($ip = OAInput::post ('ip')) && ($ip = trim ($ip)))) 
      return $this->output_json (array ('s' => true));

    BaishatunBlacklist::create (array (
        'ip' => $ip,
      ));
    
    $this->_put_mag ();

    return $this->output_json (array ('s' => true));
  }
  public function mag () {
    // http://pic.mazu.ioa.tw/upload/baishatun/mags.json
    if (!(($msg = OAInput::post ('msg')) && ($msg = trim ($msg)))) 
      return $this->output_json (array ('s' => true));

    $user_id = ($user_id = OAInput::post ('user_id')) ? $user_id : 0;
    $ip = $this->input->ip_address ();
    BaishatunMessage::create (array (
        'user_id' => $user_id,
        'ip' => $ip,
        'message' => $msg,
      ));

    $this->_put_mag ();

    return $this->output_json (array ('s' => true));
  }
}
