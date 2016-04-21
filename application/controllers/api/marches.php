<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Marches extends Api_controller {
  
  private $march = null;

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $marches = array_map (function ($march) {
      return array_merge ($march->to_array (), array ('b' => $march->last_path ? $march->last_path->battery : -1));
    }, March::find ('all', array ('select' => 'id,title AS t,is_enabled AS e', 'conditions' => array ('is_enabled = 1'))));

    return $this->output_json ($marches);
  }

}
