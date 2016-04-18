<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March extends OaModel {

  static $table_name = 'marches';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );


  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function paths2 ($limit = 0) {
    $paths = MarchPath::find ('all', array ('select' => 'id,latitude2,longitude2,time_at', 'limit' => $limit, 'order' => 'id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $this->id, $this->is_ios)));

    return array (
        't' => isset ($paths[0]) ? $paths[0]->time_at->format ('Y-m-d H:i:s') : '',
        'p' => array_map (function ($path) {
            return array (
                  'a' => $path->latitude2,
                  'n' => $path->longitude2,
                );
          }, $paths)
      );
  }
  public function paths ($is_GPS = true, $is_snap2roads = false, $limit = 0) {
    $is_ios = $this->is_ios;
    
    if ($is_GPS) {
      // $first = MarchPath::first (array ('select' => 'id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $this->id,  $is_ios)));
      $last = MarchPath::last (array ('select' => 'id,latitude2,longitude2,time_at', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $this->id,  $is_ios)));

      $point_ids = array ();
      if (!($all_ids = column_array (MarchPath::find ('all', array ('select' => 'id', 'limit' => $limit, 'order' => 'id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $this->id, $is_ios))), 'id')))
        return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());

      if (!$is_snap2roads) {
        $c = count ($all_ids);
        $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.01 : 0.05 : 0.15 : 0.3 : 0.46 : 1 : 1.5 : 2.3 : 3;
        for ($i = 0; ($key = round (($i * (2 + ($i - 1) * $unit)) / 2)) < $all_ids[0]; $i++)
          if ($temp = array_slice ($all_ids, $key, 1))
            array_push ($point_ids, array_shift ($temp));
        if (!$point_ids) return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());
      } else {
        $c = count ($all_ids);
        $unit = $c / 90;
        for ($i = 0; ($key = round ($unit * $i)) < $all_ids[0]; $i++)
          if ($temp = array_slice ($all_ids, $key, 1))
            array_push ($point_ids, array_shift ($temp));
        if (!$point_ids) return array ('s' => false, 'p' => array (), 'l' => 0, 'i' => array ());
      }
      
      $paths = MarchPath::find ('all', array ('select' => 'id,latitude2,longitude2,time_at', 'order' => 'id DESC', 'conditions' => array ('id IN (?) AND march_id = ? AND is_enabled = 1 AND is_ios = ?', $point_ids, $this->id, $is_ios)));

      if ($paths[0]->id != $last->id) array_unshift ($paths, $last);
      // if ($paths[count ($paths) - 1]->id != $first->id) array_push ($paths, $first);
    } else {
      $paths = MarchPath::find ('all', array ('select' => 'id,latitude2,longitude2,time_at', 'order' => 'id DESC', 'conditions' => array ('march_id = ? AND is_enabled = 1 AND is_ios = ?', $this->id, $is_ios)));
    }
    $paths = array_map (function ($path) {
      return array (
            'i' => $path->id,
            'a' => $path->latitude2,
            'n' => $path->longitude2,
            't' => $path->time_at->format ('Y-m-d H:i:s')
          );
    }, $paths);

    $paths = array_reverse ($paths);
    $paths = array_splice ($paths, 0);

    $this->CI->load->library ('SphericalGeometry');
    $l = round (SphericalGeometry::computeLength (array_map (function ($path) {return new LatLng ($path['a'], $path['n']);}, $paths)) / 1000, 2);

    $is = array_map (function ($i) {
      return array (
          'm' => $i->msgs (),
          'a' => $i->latitude,
          'n' => $i->longitude,
        );
    }, MarchInfo::find ('all', array ('select' => 'msgs,latitude,longitude','conditions' => array ('march_id = ?', $this->id))));

    return array (
        's' => true,
        'p' => $paths,
        'l' => $l,
        'i' => $is
      );
  }
}