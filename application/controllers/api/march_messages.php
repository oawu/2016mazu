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
      Mail::send ('檢舉', array (
        'OA <comdan66@gmail.com>',
      ), array (
        '問題' => '放置 Message 到 s3 錯誤。',
        '原因' => '上一次的 .json 檔案尚未刪除！',
      ));

    return $this->output_json (array ('s' => true));
  }
  public function create () {
    if (!(($msg = OAInput::post ('msg')) && ($msg = trim ($msg)))) 
      return $this->output_json (array ('s' => true));

    $user_id = ($user_id = OAInput::post ('user_id')) ? $user_id : 0;
    $ip = $this->input->ip_address ();
    $create = MarchMessage::transaction (function () use ($user_id, $ip, $msg) {
      return verifyCreateOrm (MarchMessage::create (array (
                'user_id' => $user_id,
                'ip' => $ip,
                'message' => $msg,
              )));  
    });
    if ($create) $create = MarchMessage::put ();

    return $this->output_json (array ('s' => $create));
  }
}
