<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March19 extends Site_controller {
  
  public function __construct () {
    parent::__construct ();
    
    if (in_array ($this->uri->rsegments (2, 0), array ('dintao', 'iko')))
      $this->add_tab ('陣頭路關', array ('href' => base_url ($this->get_class (), 'dintao'), 'index' => 1))
           ->add_tab ('藝閣路關', array ('href' => base_url ($this->get_class (), 'iko'), 'index' => 2));
  }
  public function index () {
    $prev = null;
    $next = array (
        'url' => base_url ($this->get_class (), 'dintao'),
        'title' => '陣頭路關'
      );
    $this->set_subtitle ('北港廟會')
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
  }
  public function dintao () {
    $prev = array (
        'url' => base_url ($this->get_class ()),
        'title' => '北港廟會'
      );
    $next = array (
        'url' => base_url ($this->get_class (), 'iko'),
        'title' => '藝閣路關'
      );
    $this->set_tab_index (1)
         ->set_subtitle ('陣頭路關')
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
  }
  public function iko () {
    $prev = array (
        'url' => base_url ($this->get_class (), 'dintao'),
        'title' => '陣頭路關'
      );
    $next = array (
        'url' => base_url ('maps', 'dintao'),
        'title' => '陣頭地圖'
      );
    $this->set_tab_index (2)
         ->set_subtitle ('藝閣路關')
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
  }
}
