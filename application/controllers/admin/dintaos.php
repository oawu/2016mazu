<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintaos extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    $this->add_tab ('駕前陣頭', array ('href' => base_url ('admin', $this->get_class (), 'index', 1), 'index' => 1))
         ->add_tab ('地方陣頭', array ('href' => base_url ('admin', $this->get_class (), 'index', 2), 'index' => 2))
         ->add_tab ('其他介紹', array ('href' => base_url ('admin', $this->get_class (), 'index', 3), 'index' => 3));
  }
  public function index ($index = 1) {
    $this->set_tab_index ($index)
         ->load_view ();
  }
}
