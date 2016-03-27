<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March_messages extends Api_controller {
  
  private $msg = null;

  public function __construct () {
    parent::__construct ();
// echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
// var_dump ($this->uri->rsegments (3, 0));
// exit ();
    // if (!(($id = $this->uri->rsegments (3, 0)) && ($this->msg = MarchMessage::find_by_id ($id))))
      // return $this->disable ($this->output_error_json ('Parameters error!'));
  }

  public function report () {
    if (!(($id = OAInput::post ('id')) && ($msg = MarchMessage::find_by_id ($id)))) 
      return $this->output_json (array ('s' => true));

    $msg->black_count += 1;
    if ($msg->save () && $msg->black_count > 3)
      $this->_error ();

    return $this->output_json (array ('s' => true));
  }
  public function create () {
    if (!(($msg = OAInput::post ('msg')) && ($msg = trim ($msg)))) 
      return $this->output_json (array ('s' => true));

    $user_id = ($user_id = OAInput::post ('user_id')) ? $user_id : 0;
    $ip = $this->input->ip_address ();
    MarchMessage::create (array (
        'user_id' => $user_id,
        'ip' => $ip,
        'message' => $msg,
      ));

    return $this->output_json (array ('s' => $this->_put_mag ()));
  }
  private function _error () {
    return false;
  }
  private function _put_mag () {
    $black_list_ips = column_array (MarchMessageBlacklist::find ('all', array ('select' => 'ip')), 'ip');
    $path = FCPATH . 'temp/march_messages.json';
    $s3_path = 'api/march/messages.json';

    if (file_exists ($path)) return $this->_error ();

    $this->load->helper ('file');
    if (!write_file ($path, json_encode (array ()))) return $this->_error ();

    $msgs = array_map (function ($msg) {
      return array (
          'a' => $msg->user_id ? true : false,
          'd' => $msg->id,
          'i' => $msg->ip,
          'm' => $msg->message,
          't' => $msg->created_at->format ('Y-m-d H:i:s')
        );
    }, MarchMessage::find ('all', array (
        'select' => 'id, ip, user_id, message, created_at',
        'limit' => 40,
        'order' => 'id DESC',
        'conditions' => $black_list_ips ? array ('ip NOT IN (?)', $black_list_ips) : array ()
      )));

    $unit = 10; //sec
    $q = 0;

    $end = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * $q) . ' minutes'));
    $start = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * ($q + 1)) . ' minutes'));

    $c = count (MarchMessage::find ('all', array ('group' => 'ip', 'conditions' => array ('created_at BETWEEN ? AND ?', $start, $end))));

    if (!write_file ($path, json_encode (array (
        's' => true,
        't' => date ('Y-m-d H:i:s'),
        'c' => $c * rand (5, 10),
        'm' => $msgs
      ))))
      return @unlink ($path);

    $this->_put_s3 ($path, $s3_path);

    return @unlink ($path);
  }
  private function _put_s3 ($path, $s3_path) {
    $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');
    $this->load->library ('S3', Cfg::system ('s3', 'buckets', $bucket));
    return S3::putObjectFile ($path, $bucket, $s3_path, S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years'))));
  }


}
