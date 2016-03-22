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

  public function index () {
  }
  public function create () {
    if (!$this->has_post ())
      return $this->output_error_json ('Not POST method!');

    $paths = ($paths = OAInput::post ('p')) ? $paths : array ();
    $paths = array_filter ($paths, array ($this, '_validation_path_posts'));

    $march = $this->march;
    $sqlite_ids = array_slice (column_array (array_filter ($paths, function (&$path) use ($march) {
      $create = MarchPath::transaction (function () use (&$path, $march) {
        if (!(verifyCreateOrm ($path = MarchPath::create (array_intersect_key (array_merge ($path, array ('march_id' => $march->id)), MarchPath::table ()->columns)))))
          return false;

        if ($march->is_finished && !($march->is_finished = 0))
          $march->save ();

        return true;
      });
      return $create;
    }), 'sqlite_id'), 0);
    
    return $this->output_json (array ('ids' => $sqlite_ids, 'paths' => array_map (function ($path) {
      return $path->to_array ();
    }, $march->paths ())));
  }
  private function _validation_path_posts (&$posts) {
    if (!(isset ($posts['i']) && is_numeric ($posts['i'] = trim ($posts['i'])))) return false; $posts['sqlite_id'] = $posts['i']; unset ($posts['i']);    
    if (!(isset ($posts['a']) && is_numeric ($posts['a'] = trim ($posts['a'])))) return false; $posts['latitude'] = $posts['a']; unset ($posts['a']);
    if (!(isset ($posts['n']) && is_numeric ($posts['n'] = trim ($posts['n'])))) return false; $posts['longitude'] = $posts['n']; unset ($posts['n']);
    $posts['latitude2'] = $posts['latitude'];
    $posts['longitude2'] = $posts['longitude'];

    if (!(isset ($posts['h']) && is_numeric ($posts['h'] = trim ($posts['h'])))) return false; $posts['accuracy_horizontal'] = $posts['h']; unset ($posts['h']);
    if (!(isset ($posts['v']) && is_numeric ($posts['v'] = trim ($posts['v'])))) return false; $posts['accuracy_vertical'] = $posts['v']; unset ($posts['v']);
    if (!(isset ($posts['l']) && is_numeric ($posts['l'] = trim ($posts['l'])))) return false; $posts['altitude'] = $posts['l']; unset ($posts['l']);
    if (!(isset ($posts['s']) && is_numeric ($posts['s'] = trim ($posts['s'])))) return false; $posts['speed'] = $posts['s']; unset ($posts['s']);
    if (!(isset ($posts['t']) && $posts['t'] = trim ($posts['t']))) return false; $posts['create_time'] = $posts['t']; unset ($posts['t']);

    return true;
  }
}
