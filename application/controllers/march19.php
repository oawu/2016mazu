<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class March19 extends Site_controller {

  public function about ($index = '#活動簡介') {
    $index = trim (urldecode ($index));

    $this->add_tab ('活動簡介', array ('href' => base_url ($this->get_class (), 'about', '#活動簡介'), 'index' => '#活動簡介'))
         ->add_tab ('活動時間', array ('href' => base_url ($this->get_class (), 'about', '#活動時間'), 'index' => '#活動時間'))
         ->add_tab ('路關細節', array ('href' => base_url ($this->get_class (), 'about', '#路關細節'), 'index' => '#路關細節'))
         ->add_tab ('參與陣頭', array ('href' => base_url ($this->get_class (), 'about', '#參與陣頭'), 'index' => '#參與陣頭'))
         ->add_tab ('注意事項', array ('href' => base_url ($this->get_class (), 'about', '#注意事項'), 'index' => '#注意事項'))
         ;

    $this->set_tab_index ($index)
         ->set_subtitle ($index)
         ->load_view (array (
            'index' => $index
          ));
  }
}
