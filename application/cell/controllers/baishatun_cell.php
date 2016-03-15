<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Baishatun_cell extends Cell_Controller {

  /* render_cell ('baishatun_cell', 'api', var1, ..); */
  public function _cache_api ($class = 'BaishatunShowtaiwan1Path', $id) {
    return array ('time' => 60 * 2, 'key' => $class . '_' . $id);
  }

  public function api ($class = 'BaishatunShowtaiwan1Path', $id) {
    if ($id == 0) {
      $last = $class::last (array ('select' => 'id,lat,lng,lat2,lng2,time_at'));

      $point_ids = array ();
      if (!($all_point_ids = column_array ($class::find ('all', array ('select' => 'id', 'order' => 'id DESC')), 'id')))
        return $point_ids;

      $c = count ($all_point_ids);
      $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.01 : 0.05 : 0.15 : 0.3 : 0.46 : 1 : 1.5 : 2.3 : 3;
      for ($i = 0; ($key = round (($i * (2 + ($i - 1) * $unit)) / 2)) < $all_point_ids[0]; $i++)
        if ($temp = array_slice ($all_point_ids, $key, 1))
          array_push ($point_ids, array_shift ($temp));
      if (!$point_ids) return $point_ids;
      
      $paths = $class::find ('all', array ('select' => 'id,lat,lng,lat2,lng2,time_at', 'order' => 'id DESC', 'conditions' => array ('id IN (?)', $point_ids)));
      if ($paths[0]->id == $last->id) array_unshift ($paths, $last);

    } else {
      $paths = array_reverse ($class::find ('all', array ('select' => 'id,lat,lng,lat2,lng2,time_at', 'conditions' => array ('id > ?', $id))));
    }

    $paths = array_map (function ($path) {
      return array (
            'i' => $path->id,
            'a' => isset ($path->lat2) && ($path->lat2 != '') ? $path->lat2 : $path->lat,
            'n' => isset ($path->lng2) && ($path->lng2 != '') ? $path->lng2 : $path->lng,
            't' => $path->time_at->format ('Y-m-d H:i:s')
          );
    }, $paths);

    return array_splice (array_reverse ($paths), 0);
  }
}