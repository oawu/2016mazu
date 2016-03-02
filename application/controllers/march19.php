<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March19 extends Site_controller {
  
  public function __construct () {
    parent::__construct ();
  
    $this->add_tab ('陣頭路關', array ('href' => base_url ($this->get_class (), 'dintao'), 'index' => 1))
         ->add_tab ('藝閣路關', array ('href' => base_url ($this->get_class (), 'iko'), 'index' => 2));
  }
  public function dintao () {
    $this->set_tab_index (1)
         ->set_subtitle ('陣頭路關')
         ->load_view ();
  }
  public function iko () {
    $this->set_tab_index (2)
         ->set_subtitle ('藝閣路關')
         ->load_view ();
  }
}
