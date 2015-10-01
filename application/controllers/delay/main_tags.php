<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Main_tags extends Delay_controller {

  public function add_tags () {
    if (!(($str = OAInput::post ('str')) && ($names = preg_split ("/\s+/", $str))))
      return;
    foreach ($names as $name)
      MainTag::find_or_create_by_name ($name);
  }
}