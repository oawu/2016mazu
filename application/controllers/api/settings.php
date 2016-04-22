<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Settings extends Api_controller {
  
  private $setting = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'show')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->setting = GpsSetting::find ('one', array ('conditions' => array ('id = ?', $id))))))
        return $this->disable ($this->output_error_json ('Parameters error!'));
  }
  public function show () {
    return $this->output_json ($this->setting->to_array ());
  }
}
