<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends Site_controller {

  public function index () {
    $this->add_subtitle ('角色列表');
    $this->add_tab ('aaa1', array ('href' => 'dasdsa', 'class' => 'main', 'method' => ''));
    $this->add_tab ('aaa2', array ('href' => 'dasdsa', 'class' => '', 'method' => ''));
    $this->add_tab ('aaa3', array ('href' => 'dasdsa', 'class' => '', 'method' => ''));
    $this->add_tab ('aaa4', array ('href' => 'dasdsa', 'class' => '', 'method' => ''));
    $this->add_tab ('aaa5', array ('href' => 'dasdsa', 'class' => '', 'method' => ''));
    $this->load_view ();
  }
}
