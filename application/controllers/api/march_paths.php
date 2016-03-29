<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March_paths extends Api_controller {
  
  private $march = null;

  public function __construct () {
    parent::__construct ();

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->march = March::find_by_id ($id))))
      return $this->disable ($this->output_error_json ('Parameters error!'));
  }

  public function create () {
    $paths = ($paths = OAInput::post ('p')) ? $paths : array ();
    if (!$paths) return $this->output_json (array ('ids' => array ()));
// var_dump ($paths);
// exit ();
    $march = $this->march;
    $last = MarchPath::find ('one', array (
        'select' => 'time_at',
        'order' => 'time_at DESC',
        'conditions' => array ('march_id = ?', $march->id)
      ));

    $paths = array_filter (array_map (function ($post) use ($last) {
      if (!(isset ($post['id']) && is_numeric ($post['id'] = trim ($post['id'])))) return null;
      if (!(isset ($post['a']) && is_numeric ($post['a'] = trim ($post['a'])))) return null;
      if (!(isset ($post['n']) && is_numeric ($post['n'] = trim ($post['n'])))) return null;
      if (!(isset ($post['h']) && is_numeric ($post['h'] = trim ($post['h'])))) return null;
      if (!(isset ($post['v']) && is_numeric ($post['v'] = trim ($post['v'])))) return null;
      if (!(isset ($post['l']) && is_numeric ($post['l'] = trim ($post['l'])))) return null;
      if (!(isset ($post['s']) && is_numeric ($post['s'] = trim ($post['s'])))) return null;
      if (!(isset ($post['i']) && is_numeric ($post['i'] = trim ($post['i'])))) return null;
      if (!(isset ($post['b']) && is_numeric ($post['b'] = trim ($post['b'])))) return null;
      if (!(isset ($post['t']) && ($post['t'] = trim ($post['t'])))) return null;
      
      $post['sqlite_id'] = $post['id'];
      $post['latitude'] = $post['a'];
      $post['longitude'] = $post['n'];
      $post['accuracy_horizontal'] = $post['h'];
      $post['accuracy_vertical'] = $post['v'];
      $post['altitude'] = $post['l'];
      $post['speed'] = $post['s'];
      $post['is_ios'] = $post['i'];
      $post['battery'] = $post['b'];
      $post['time_at'] = $post['t'];
      $post['latitude2'] = $post['latitude'];
      $post['longitude2'] = $post['longitude'];
      $post['is_enabled'] = $post['accuracy_horizontal'] < 100 ? 1 : 0;

      unset ($post['id'], $post['a'], $post['n'], $post['h'], $post['v'], $post['l'], $post['s'], $post['i'], $post['b'], $post['t']);

      return !$last || $post['time_at'] > $last->time_at ? $post : null;
    }, $paths));
  
    if (!$paths) return $this->output_json (array ('ids' => array ()));
  
    usort ($paths, function ($a, $b) {
      return $a['time_at'] > $b['time_at'];
    });

    $paths = array_filter ($paths, function ($path) use ($march) {
      return MarchPath::transaction (function () use ($path, $march) {
        return verifyCreateOrm (MarchPath::create (array_intersect_key (array_merge ($path, array ('march_id' => $march->id)), MarchPath::table ()->columns)));
      });
    });

    $paths = column_array ($paths, 'sqlite_id');
    $sqlite_ids = array_slice ($paths, 0);
    
    return $this->output_json (array ('ids' => $sqlite_ids));
  }

}
