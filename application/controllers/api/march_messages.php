<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March_messages extends Api_controller {
  
  private $msg = null;

  public function __construct () {
    parent::__construct ();
  }

  public function report () {
    if (!(($id = OAInput::post ('id')) && ($msg = MarchMessage::find_by_id ($id)))) 
      return $this->output_json (array ('s' => true));

    $msg->black_count += 1;
    if ($msg->save () && $msg->black_count >= 3) {
      if (!$msg->token && $msg->token = md5 ($msg->id . '_' . uniqid ()))
        $msg->save ();

      Mail::send ('檢舉', array (
        'OA <comdan66@gmail.com>',
      ), array (
        '訊息內容' => $msg->message,
        '來源 IP' => $msg->ip,
        '檢舉次數' => $msg->black_count,
        '黑名單 IP' => "<a href='" . base_url ('api', 'march_messages', 'black', $msg->token) . "'>列為黑名單</a>"
      ));
    }

    return $this->output_json (array ('s' => true));
  }
  public function black ($token) {
    if (!($token && ($msg = MarchMessage::find_by_token ($token, array ('select' => 'token, ip')))))
      return $this->output_json (array ('NO - 找不到檔案..'));

    if ($black = MarchMessageBlacklist::find_by_ip ($msg->ip))
      return $this->output_json (array ('YES - 早就黑名單囉！'));

    $create = MarchMessageBlacklist::transaction (function () use ($msg) {
      return verifyCreateOrm (MarchMessageBlacklist::create (array (
                'ip' => $msg->ip,
              )));  
    });

    if ($create) $create = MarchMessage::put ();

    return $this->output_json (array ($create ? 'YES - 黑名單成功！' : 'NO - 黑名單失敗..'));
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
