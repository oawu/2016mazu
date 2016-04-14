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

  public function last () {
    $is_ios = $this->march->is_ios;
    if (!$last = MarchPath::last (array ('conditions' => array ('march_id = ? AND is_ios = ?', $this->march->id,  $is_ios)))) return $this->output_error_json ('No Any Data！');

    return $this->output_json (array ('last' => $last->to_array ()));
  }

  private function _o ($paths) {
    if (!$paths) return $paths;
    $tmp_path = array_filter ($paths, function ($path) {
      return $path['accuracy_horizontal'] < 31;
    });

    if (!$tmp_path) return $paths;
    $url = 'https://roads.googleapis.com/v1/snapToRoads';
    $url .= '?' . http_build_query (array ('key' => Cfg::setting ('google', ENVIRONMENT, 'server_key'), 'interpolate' => true, 'path' => implode ('|', array_map (function ($path) {
          return $path['latitude'] . ',' . $path['longitude'];
        }, $tmp_path))));

    $res = file_get_contents ($url);
    $res = json_decode ($res, true);
    if (!(isset ($res['snappedPoints']) && is_array ($res['snappedPoints']) && $res['snappedPoints']))
      return $paths;

    foreach ($res['snappedPoints'] as $i => $point) {
      if (!(isset ($point['location']['latitude']) && isset ($point['location']['longitude'])))
        continue;

      if (isset ($paths[$i])) {
        $paths[$i]['latitude2'] = $point['location']['latitude'];
        $paths[$i]['longitude2'] = $point['location']['longitude'];
        $paths[$i]['is_enabled'] = 1;
      } else {
        array_push ($paths, array_merge ($paths[count ($paths) - 1], array (
            'latitude2' => $point['location']['latitude'],
            'longitude2' => $point['location']['longitude'],
            'is_enabled' => 1,
          )));
      }
    }

    return $paths;
  }

  public function create () {
    $paths = ($paths = OAInput::post ('p')) ? $paths : array ();
    $same = ($same = OAInput::post ('s')) ? $same : false;
    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    var_dump ($same ? "1" : "0");
    exit ();
    if (!$paths) return $this->output_json (array ('ids' => array ()));

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
      $post['is_enabled'] = 0;
      $post['is_enabled'] = $post['accuracy_horizontal'] <= 100 ? 1 : 0;

      unset ($post['id'], $post['a'], $post['n'], $post['h'], $post['v'], $post['l'], $post['s'], $post['i'], $post['b'], $post['t']);

      return !$last || $post['time_at'] > $last->time_at ? $post : null;
    }, $paths));
  
    if (!$paths) return $this->output_json (array ('ids' => array ()));

    usort ($paths, function ($a, $b) {
      return $a['time_at'] > $b['time_at'];
    });

    // $paths = $this->_o ($paths);

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
