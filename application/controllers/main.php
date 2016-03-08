<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Main extends Site_controller {

  public function spec () {
    $this->set_frame_path ('frame', 'pure')
         ->load_view ();
  }
  public function index () {
    $this->set_title ('北港朝天宮')
         ->set_subtitle ('北港朝天宮')
         ->load_view ();
  }
}
