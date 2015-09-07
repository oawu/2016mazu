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
    $this->load->library ('OaMailGun');
    $mail = new OaMailGun ();
    $result = $mail->sendMessage (array (
              'from' => Cfg::setting ('mail_gun', 'user', 'system', 'name') . ' <' . Cfg::setting ('mail_gun', 'user', 'system', 'email') . '>',
              'to' => $temp->name . ' <' . $temp->email . '>',
              'subject' => Cfg::setting ('mail_gun', 'user', 'system', 'subject'),
              'html' => $msg
            ));
    // $this->load_view (null);
  }
}
