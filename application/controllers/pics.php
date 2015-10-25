<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pics extends Site_controller {

  public function index ($a) {
      echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
      var_dump ($a);
      exit ();
  }
  public function show ($a) {
      echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
      var_dump ($a);
      exit ();
  }
  public function add ($a = 0, $b = 0) {
  }
  public function create ($a = 0, $b = 0) {
  }
}
