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
    $paths = array_map (function (&$post) {
      if (!(isset ($post['id']) && is_numeric ($post['id'] = trim ($post['id'])))) return false; $post['sqlite_id'] = $post['id']; unset ($post['id']);    
      if (!(isset ($post['a']) && is_numeric ($post['a'] = trim ($post['a'])))) return false; $post['latitude'] = $post['a']; unset ($post['a']);
      if (!(isset ($post['n']) && is_numeric ($post['n'] = trim ($post['n'])))) return false; $post['longitude'] = $post['n']; unset ($post['n']);
      if (!(isset ($post['h']) && is_numeric ($post['h'] = trim ($post['h'])))) return false; $post['accuracy_horizontal'] = $post['h']; unset ($post['h']);
      if (!(isset ($post['v']) && is_numeric ($post['v'] = trim ($post['v'])))) return false; $post['accuracy_vertical'] = $post['v']; unset ($post['v']);
      if (!(isset ($post['l']) && is_numeric ($post['l'] = trim ($post['l'])))) return false; $post['altitude'] = $post['l']; unset ($post['l']);
      if (!(isset ($post['s']) && is_numeric ($post['s'] = trim ($post['s'])))) return false; $post['speed'] = $post['s']; unset ($post['s']);
      if (!(isset ($post['t']) && $post['t'] = trim ($post['t']))) return false; $post['create_time'] = $post['t']; unset ($post['t']);
      $post['latitude2'] = $post['latitude'];
      $post['longitude2'] = $post['longitude'];

      return $post;
    }, $paths);

    $march = $this->march;
    $paths = array_filter ($paths, function (&$path) use ($march) {
      $create = MarchPath::transaction (function () use (&$path, $march) {
        if (!(verifyCreateOrm ($path = MarchPath::create (array_intersect_key (array_merge ($path, array ('march_id' => $march->id)), MarchPath::table ()->columns)))))
          return false;

        if ($march->is_finished && !($march->is_finished = 0))
          $march->save ();

        return true;
      });
      return $create;
    });
    $paths = column_array ($paths, 'sqlite_id');
    $sqlite_ids = array_slice ($paths, 0);
    
    return $this->output_json (array ('ids' => $sqlite_ids));
  }

}
