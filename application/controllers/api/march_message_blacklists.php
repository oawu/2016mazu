<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March_message_blacklists extends Api_controller {
  
  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $list = MarchMessageBlacklist::all (array ('select' => 'id,ip'));

    $list = array_map (function ($item) {
      return $item->to_array ();
    }, $list);

    return $this->output_json (array ('l' => $list));
  }
}
