<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $this->load->library ('fb');

    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    echo "<a href='" . $this->fb->login_url ('platform', 'fb_sign_in', 'main', 'index') . "'>a</a>";
    var_dump (Session::getData ('_fb_sign_in_message', true));
    exit ();
    // $msg = 'Hi OA,<br/><br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;非常感謝您的加入，現在只差最後一個步驟了，請點擊下列網址以啟動您的帳號吧！<br/><br/>驗證網址: <br/>';

    // $this->load->library ('OaMailGun');
    // $mail = new OaMailGun ();
    // $result = $mail->sendMessage (array (
    //           'from' => Cfg::setting ('mail_gun', 'user', 'system', 'name') . ' <' . Cfg::setting ('mail_gun', 'user', 'system', 'email') . '>',
    //           'to' => 'OA' . ' <' . 'comdan66@gmail.com' . '>',
    //           'subject' => Cfg::setting ('mail_gun', 'user', 'system', 'subject'),
    //           'html' => $msg
    //         ));
    // $this->load_view (null);
  }
}
