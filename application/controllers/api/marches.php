<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Marches extends Api_controller {
  
  private $march = null;

  public function __construct () {
    parent::__construct ();

    if (!(in_array ($this->uri->rsegments (2, 0), array ('enable')) && ($id = $this->uri->rsegments (3, 0)) && ($this->march = March::find_by_id ($id))))
      return $this->disable ($this->output_error_json ('Parameters error!'));
  }

  public function enable ($id = 0) {
    $is_enabled = is_numeric ($is_enabled = OAInput::post ('is_enabled')) ? $is_enabled : 0;
    $this->march->is_enabled = $is_enabled;
    $march = $this->march;
    if ($update = March::transaction (function () use ($march) { return $march->save (); }))
      return $this->output_json ($march->to_array ());
  }
  public function index () {
    $marches = array_map (function ($march) {
      return array_merge ($march->to_array (), array ('b' => $march->last_path ? $march->last_path->battery : -1));
    }, March::find ('all', array ('select' => 'id,title AS t,is_enabled AS e', 'conditions' => array ('is_enabled = 1'))));

    return $this->output_json ($marches);
  }

}
