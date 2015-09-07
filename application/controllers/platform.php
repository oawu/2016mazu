<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Platform extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $this->load_view (null);
  }

  public function fb_sign_in () {
    $this->load->library ('fb');

    if ($this->fb->login () && ($me = $this->fb->me ()) && ((isset ($me['name']) && ($name = $me['name'])) && (isset ($me['id']) && ($id = $me['id'])))) {
      $name = $me['name'];
      $id = $me['id'];

      if ((($user = User::find ('one', array ('conditions' => array ('uid = ?', $id)))) && ($user->name = $name) && $user->save ())
            ||
            verifyCreateOrm ($user = User::create (array ('uid' => $id, 'name' => $name)))) {
        Session::setData ('user_id', $user_id);
        Session::setData ('_fb_sign_in_message', '使用 Facebook 登入成功!', true);
      } else {
        Session::setData ('_fb_sign_in_message', 'Facebook 登入錯誤，請通知程式設計人員!(2)', true);
      }
    } else
      Session::setData ('_fb_sign_in_message', 'Facebook 登入錯誤，請通知程式設計人員!(1)', true);

    redirect (func_get_args (), 'refresh');
  }

  public function sign_out () {
    Session::setData ('user_id', 0);
    Session::setData ('_fb_sign_in_message', '登出成功!', true);

    redirect (func_get_args (), 'refresh');
  }
}
