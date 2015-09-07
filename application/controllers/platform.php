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
    if (facebook ()->login () && ($me = facebook ()->me ()) && ((isset ($me['name']) && ($name = $me['name'])) && (isset ($me['id']) && ($id = $me['id'])))) {
      $name = $me['name'];
      $id = $me['id'];

      if ((($user = User::find ('one', array ('conditions' => array ('uid = ?', $id)))) && ($user->name = $name) && $user->save ())
            ||
            verifyCreateOrm ($user = User::create (array ('uid' => $id, 'name' => $name))))

        identity ()->set_session ('user_id', $user->id)
                   ->set_session ('fb_uid', $user->uid)
                   ->set_session ('fb_name', $user->name)
                   ->set_session ('_fb_sign_in_message', '使用 Facebook 登入成功!', true);
      else
        identity ()->set_session ('_fb_sign_in_message', 'Facebook 登入錯誤，請通知程式設計人員!', true);
    } else
      identity ()->set_session ('_fb_sign_in_message', 'Facebook 登入錯誤，請通知程式設計人員!', true);

    redirect (func_get_args (), 'refresh');
  }

  public function sign_out () {
    identity ()->set_identity ('sign_out')
               ->set_session ('fb_uid', 0)
               ->set_session ('fb_name', '')
               ->set_session ('_fb_sign_in_message', '登出成功!', true);

    redirect (func_get_args (), 'refresh');
  }
}
