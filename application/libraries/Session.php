<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Session {
  private static $CI = null;

  public function __construct () {
  }

  private static function ci () {
    if (self::$CI === null)
      self::$CI =& get_instance ();
    return self::$CI;
  }

  protected static function session () {
    if (isset (self::ci ()->ci_session))
      return self::ci ()->ci_session;

    if (!class_exists ('CI_Session'))
      include_once FCPATH . 'system' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Session.php';

    return self::ci ()->ci_session = new CI_Session ();
  }

  public static function setData ($key, $value, $is_flashdata = false) {
    if (!$is_flashdata)
      self::session ()->set_userdata ($key, $value);
    else
      self::session ()->set_flashdata ($key, $value);
  }

  public static function getData ($key, $is_flashdata = false) {
    return !$is_flashdata ? self::session ()->userdata ($key) : self::session ()->flashdata ($key);
  }
}
