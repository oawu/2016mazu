<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March_users extends Api_controller {
  
  private $march = null;

  public function __construct () {
    parent::__construct ();

  }

  public function create () {
    if (!(($a = OAInput::post ('a')) && ($n = OAInput::post ('n'))))
      return $this->output_json (array ('s' => false));
    
    $ip = $this->input->ip_address ();

    $create = MarchUser::transaction (function () use ($ip, $a, $n) {
        return verifyCreateOrm (MarchUser::create (array (
            'ip'  => isset ($ip) && $ip ? $ip : '0.0.0.0',
            'latitude' => $a,
            'longitude' => $n,
          )));
      });
    return $this->output_json (array ('s' => $create));
  }
}
