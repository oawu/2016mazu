<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Platform extends Site_controller {

  public function login () {
    if (User::current () && User::current ()->is_login ())
      return redirect_message (array (''), array ());
    else
      $this->load_view ();
  }
  public function fb_sign_in () {
    if (!(Fb::login () && ($me = Fb::me ()) && ((isset ($me['name']) && ($name = $me['name'])) && (isset ($me['email']) && ($email = $me['email'])) && (isset ($me['id']) && ($id = $me['id'])))))
      return redirect_message (array (), array (
          '_flash_message' => 'Facebook 登入錯誤，請通知程式設計人員!(1)'
        ));

    if (!($user = User::find ('one', array ('conditions' => array ('uid = ?', $id)))))
      if (!User::transaction (function () use (&$user, $id) { return verifyCreateOrm ($user = User::create (array_intersect_key (array ('uid' => $id), User::table ()->columns))); }))
        return redirect_message (array (), array (
            '_flash_message' => 'Facebook 登入錯誤，請通知程式設計人員!(2)'
          ));

    $user->name = $name;
    $user->email = $email;
    $user->logined_at = date ('Y-m-d H:i:s');

    if (!User::transaction (function () use ($user) { return $user->save (); }))
      return redirect_message (array (), array (
          '_flash_message' => 'Facebook 登入錯誤，請通知程式設計人員!(3)'
        ));

    Session::setData ('user_id', $user->id);

    return redirect_message (func_get_args (), array (
        '_flash_message' => '使用 Facebook 登入成功!'
      ));
  }

  public function sign_out () {
    Session::setData ('user_id', 0);

    return redirect_message (func_get_args (), array (
        '_flash_message' => '登出成功!'
      ));
  }
}
