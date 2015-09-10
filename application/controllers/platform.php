<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Platform extends Site_controller {

  public function fb_sign_in () {
    if (Fb::login () && ($me = Fb::me ()) && ((isset ($me['name']) && ($name = $me['name'])) && (isset ($me['email']) && ($email = $me['email'])) && (isset ($me['id']) && ($id = $me['id'])))) {
      if ((($user = User::find ('one', array ('conditions' => array ('uid = ?', $id)))) && ($user->name = $name) && ($user->email = $email) && ($user->logined_at = date ('Y-m-d H:i:s')) && $user->save ()) || verifyCreateOrm ($user = User::create (array ('uid' => $id, 'name' => $name, 'email' => $email, 'logined_at' => date ('Y-m-d H:i:s'))))) {
        Session::setData ('user_id', $user->id);
        Session::setData ('_flash_message', '使用 Facebook 登入成功!', true);
      } else {
        Session::setData ('_flash_message', 'Facebook 登入錯誤，請通知程式設計人員!(2)', true);
      }
    } else {
      Session::setData ('_flash_message', 'Facebook 登入錯誤，請通知程式設計人員!(1)', true);
    }

    return redirect (func_get_args (), 'refresh');
  }

  public function sign_out () {
    Session::setData ('user_id', 0);
    Session::setData ('_flash_message', '登出成功!', true);

    return redirect (func_get_args (), 'refresh');
  }
}
