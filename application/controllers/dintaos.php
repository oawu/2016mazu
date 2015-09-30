<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintaos extends Site_controller {

  public function __construct () {
    parent::__construct ();

  }
  public function official ($offset = 0) {
    $dintaos = render_cell ('dintao_cell', 'dintaos', $this->get_class (), $this->get_method (), $offset);

    $this->set_method ('list')
         ->add_subtitle ('朝天宮 駕前陣頭')
         ->load_view (array (
        'method' => 'official',
        'dintaos' => $dintaos
      ));
  }
}
