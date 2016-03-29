<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Clean extends Api_controller {
  

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $marches = March::find ('all', array ('select' => 'id', 'conditions' => array ('is_enabled = 1')));

    foreach ($marches as $march)
      @unlink (FCPATH . 'temp/march_' . $march->id . '_paths.json');

    @unlink (FCPATH . 'temp/march_messages.json');

    return $this->output_json (array (
        's' => true
      ));
  }
}
