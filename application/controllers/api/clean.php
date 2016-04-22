<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Clean extends Api_controller {
  

  public function __construct () {
    parent::__construct ();
  }

  public function messages () {
    if (@unlink (FCPATH . 'temp/march_messages.json'))
      return $this->output_json ('清除成功');
    else
      return $this->output_error_json ('清除失敗！');
  }

  public function gps () {
    if (@unlink (FCPATH . 'temp/march_gps.json'))
      return $this->output_json ('清除成功');
    else
      return $this->output_error_json ('清除失敗！');
  }
  
  public function temp () {
    $this->load->helper ('directory');
    directory_delete (FCPATH . 'temp', false);
    return $this->output_json ('清除成功');
  }










  // private function _paths () {
  //   $marches = March::find ('all', array ('select' => 'id', 'conditions' => array ('is_enabled = 1')));

  //   foreach ($marches as $march)
  //     @unlink (FCPATH . 'temp/march_' . $march->id . '_paths.json');

  //   return true;
  // }
  // private function _heatmaps () {
  //   for ($i = 0; $i < 10; $i++)
  //     @unlink (FCPATH . 'temp/march_' . $i . '_heatmaps.json');
  //   return true;
  // }
  // public function paths () {
  //   return $this->output_json (array ('s' => $this->_paths ()));
  // }
  // public function heatmaps () {
  //   return $this->output_json (array ('s' => $this->_heatmaps ()));
  // }
  // public function all_jsons () {
  //   return $this->output_json (array ('s' => $this->_paths () && $this->_messages () && $this->_heatmaps ()));
  // }
}
