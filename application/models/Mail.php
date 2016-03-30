<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Mail extends OaModel {

  static $table_name = 'mails';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public function html () {
    $html = "<article style='font-size:15px;line-height:22px;color:rgb(85,85,85)'><p style='margin-bottom:0'>Hi 管理員,</p><section style='padding:5px 20px'><p>剛剛發生了系統異常的狀況，以下是錯誤訊息：</p><table style='width:100%;border-collapse:collapse'><tbody>";
    foreach ($this->messages () as $title => $message)
      $html .= "<tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>" . $title . "：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $message . "</td></tr>";
    $html .= "</tbody></table><br/><p style='text-align:right'>如果有任何問題，可以向管理員 - <a href='http://www.facebook.com/comdan66' style='color:rgba(96,156,255,1);margin:0 2px'>吳政賢</a>詢問。</p></section></article>";
    
    return $html;
  }
  public function messages () {
    return !$this->messages ? array () : json_decode ($this->messages);
  }
  public function users () {
    return !$this->users ? array () : json_decode ($this->users);
  }
  public function to () {
    return implode (', ', $this->users ());
  }
  public static function send ($title, $users, $messages) {
    $mail = null;
    $create = Mail::transaction (function () use (&$mail, $title, $users, $messages) {
      return verifyCreateOrm ($mail = Mail::create (array (
                'title' => $title,
                'messages' => json_encode ($messages),
                'users' => json_encode ($users),
                'status' => 0,
              )));
    });
    if (!($create && $mail && $mail->users ()))
      return false;

    $CI =& get_instance ();
    $CI->load->library ('OaMailGun');
    $mailGun = new OaMailGun ();
    $result = $mailGun->sendMessage (array (
              'from' => Cfg::setting ('mail_gun', 'user', 'system', 'name') . ' <' . Cfg::setting ('mail_gun', 'user', 'system', 'email') . '>',
              'to' => $mail->to (),
              'subject' => '[' . $mail->title . '] ' . Cfg::setting ('mail_gun', 'user', 'system', 'subject'),
              'html' => $mail->html ()
            ));

    if ($result->http_response_code != 200)
      return false;
    
    $mail->status = 1;
    return Mail::transaction (function () use ($mail) {
      return $mail->save ();  
    });
  }
}