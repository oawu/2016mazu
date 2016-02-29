<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Google {

  protected static $path = null;
  public function __construct () {

    if (self::$path === null) {
      self::$path = explode (DIRECTORY_SEPARATOR, pathinfo (__FILE__, PATHINFO_DIRNAME));
      array_pop (self::$path);
    }

    spl_autoload_register (array ('Google', '__autoload_google'));
  }
  static function __autoload_google ($class) {
    if (stripos ($class, 'Google') !== FALSE) {
      $path = str_replace ('_', DIRECTORY_SEPARATOR, $class);
      require_once implode (DIRECTORY_SEPARATOR, self::$path) . DIRECTORY_SEPARATOR . $path . EXT;
    }
  }
}