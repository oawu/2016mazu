<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Clean extends Api_controller {
  

  public function __construct () {
    parent::__construct ();
  }

  private function _path () {
    $marches = March::find ('all', array ('select' => 'id', 'conditions' => array ('is_enabled = 1')));

    foreach ($marches as $march)
      @unlink (FCPATH . 'temp/march_' . $march->id . '_paths.json');

    return true;
  }
  private function _messages () {
    @unlink (FCPATH . 'temp/march_messages.json');
    return true;
  }
  private function _heatmaps () {
    // @unlink (FCPATH . 'temp/march_messages.json');
    return true;
  }
  public function paths () {
    return $this->output_json (array ('s' => $this->_paths ()));
  }
  public function messages () {
    return $this->output_json (array ('s' => $this->_messages ()));
  }
  public function heatmaps () {
    return $this->output_json (array ('s' => $this->_heatmaps ()));
  }
  public function all_jsons () {
    return $this->output_json (array ('s' => $this->_paths () && $this->_messages () && $this->_heatmaps ()));
  }
  public function temp () {

    return $this->output_json (array ('s' => true));
  }
}
