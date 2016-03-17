<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Baishatun extends Api_controller {
  private $version = 1;
  public function __construct () {
    parent::__construct ();
    header ('Content-type: text/html');

    if (ENVIRONMENT == 'production')
      header ('Access-Control-Allow-Origin: http://comdan66.github.io');
    else
      header ('Access-Control-Allow-Origin: *');

    $this->version = 23;
  }

  public function com ($id = 0) {
    $r = render_cell ('baishatun_cell', 'api', 'BaishatunComPath', $id);
    return $this->output_json (array (
        'v' => $this->version, 's' => true, 'p' => $r['p'], 'l' => $r['l'], 'i' => $r['i']
      ));
  }

  public function showtaiwan1 ($id = 0) {
    $r = render_cell ('baishatun_cell', 'api', 'BaishatunShowtaiwan1Path', $id);
    return $this->output_json (array (
        'v' => $this->version, 's' => true, 'p' => $r['p'], 'l' => $r['l'], 'i' => $r['i']
      ));
  }

  public function showtaiwan2 ($id = 0) {
    $r = render_cell ('baishatun_cell', 'api', 'BaishatunShowtaiwan2Path', $id);
    return $this->output_json (array (
        'v' => $this->version, 's' => true, 'p' => $r['p'], 'l' => $r['l'], 'i' => $r['i']
      ));
  }

  public function heatmap ($q = 0) {
    $q = $q < 0 ? 0 : ($q > 5 ? 4 : $q);
    $q = render_cell ('baishatun_cell', 'heatmap', $q);
    return $this->output_json (array (
        's' => true, 'q' => $q
      ));
  }
  public function location () {
    $posts = OAInput::post ();
    if (!(isset ($posts['a']) && isset ($posts['n']) && ($a = trim ($posts['a'])) && ($n = trim ($posts['n'])))) {
      BaishatunErrorLog::create (array ('message' => '[location] POST 錯誤！'));
      return;
    }
    
    $ip = $this->input->ip_address ();

    if (!verifyCreateOrm (BaishatunUser::create (array (
                'ip'  => isset ($ip) && $ip ? $ip : '0.0.0.0',
                'lat' => $a,
                'lng' => $n,
              ))))
      BaishatunErrorLog::create (array ('message' => '[location] 新增錯誤！'));
  }
  public function clear () {
    $path = FCPATH . 'temp/hi.text';
    @unlink ($path);
    if (!file_exists ($path))
      echo "OK";
    else
      echo "NO";
  }

}
