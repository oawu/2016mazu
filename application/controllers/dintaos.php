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
    $data = render_cell ('dintao_cell', 'dintaos', $this->get_class (), $this->get_method (), $offset);

    $this->set_method ('list')
         ->load_view (array (
        'dintaos' => $data['dintaos'],
        'pagination' => $data['pagination'],
      ));
  }
}
