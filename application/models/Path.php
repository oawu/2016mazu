<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Path extends OaModel {

  static $table_name = 'paths';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'PathTagMapping', 'order' => 'path_id DESC'),
    array ('tags', 'class_name' => 'PathTag', 'through' => 'mappings'),
    array ('points', 'class_name' => 'PathPoint'),
    array ('infos', 'class_name' => 'PathInfo', 'conditions' => array ('destroy_user_id IS NULL')),
  );

  static $belongs_to = array (
  );

  const D4_START_LAT = 23.569396231491233;
  const D4_START_LNG = 120.3030703338623;

  const NO_ENABLED = 0;
  const IS_ENABLED = 1;

  static $isIsEnabledNames = array(
    self::NO_ENABLED => '關閉',
    self::IS_ENABLED => '啟用',
  );

  private $mini_points = null;

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('image', 'PathImageImageUploader');
  }
  public function update_length () {
    if (!(isset ($this->id) && isset ($this->length)))
      return false;

    $length = $this->compute_length ();
    $this->length = $length;
    return $this->save ();
  }
  public function update_image () {
    if ($url = $this->picture ('1200x1200', 'server_key'))
      return $this->image->put_url ($url);
    else
      return true;
  }

  public function picture ($size = '60x60', $type = 'client_key', $color = '0x780f79', $marker_color = 'red') {
    if (count ($paths = array_map (function ($path) { return $path->lat . ',' . $path->lng; }, $this->mini_points ())) > 1)
      return 'https://maps.googleapis.com/maps/api/staticmap?path=color:' . $color . '|weight:5|' . implode ('|', $paths) . '&size=' . $size . '&markers=color:' . $marker_color . '%7C' . $paths[0] . '&language=zh-TW&key=' . Cfg::setting ('google', ENVIRONMENT, $type);
    else if ($paths && ($paths = array_shift ($paths)))
      return 'https://maps.googleapis.com/maps/api/staticmap?center=' . $paths . '&zoom=13&size=' . $size . '&markers=color:' . $marker_color . '%7C' . $paths . '&language=zh-TW&key=' . Cfg::setting ('google', ENVIRONMENT, $type);
    else
      return 'https://maps.googleapis.com/maps/api/staticmap?center=' . Path::D4_START_LAT . ',' . Polyline::D4_START_LNG . '&zoom=13&size=' . $size . '&markers=color:' . $marker_color . '%7C' . Polyline::D4_START_LAT . ',' . Polyline::D4_START_LNG . '&language=zh-TW&key=' . Cfg::setting ('google', ENVIRONMENT, $type);
  }

  public function mini_points ($select = '', $is_GS = true) {
    if ($this->mini_points !== null) return $this->mini_points;

    $point_ids = array ();
    if (!isset ($this->id)) return $point_ids;

    if (!($all_point_ids = column_array (PathPoint::find ('all', array ('select' => 'id', 'order' => 'id DESC', 'conditions' => array (
                                'path_id = ?', $this->id
                              ))), 'id')))
      return $point_ids;

    $c = count ($all_point_ids);
    $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.02 : 0.07 : 0.17 : 0.5 : 0.5 : 1.2 : 1.7 : 2.5 : 3.2;
    for ($i = 0; ($key = $is_GS ? round (($i * (2 + ($i - 1) * $unit)) / 2) : $i) < $all_point_ids[0]; $i++)
      if ($temp = array_slice ($all_point_ids, $key, 1))
        array_push ($point_ids, array_shift ($temp));

    if (!$point_ids) return $point_ids;

    return $this->mini_points = PathPoint::find ('all', array ('select' => $select ? $select : 'id, latitude AS lat, longitude AS lng', 'order' => 'id DESC', 'conditions' => array ('path_id = ? AND id IN (?)', $this->id, $point_ids)));
  }
  public function length ($unit = 'km') {
    if (!isset ($this->length))
      return 0;
    
    $length = $unit == 'km' ? $this->length / 1000 : $this->length;
    return number_format ($length, 2);
  }
  public function compute_length () {
    if (!isset ($this->id))
      return 0;

    $this->CI->load->library ('SphericalGeometry');

    return SphericalGeometry::computeLength (array_map (function ($path) {
        return new LatLng ($path->latitude, $path->longitude);
      }, PathPoint::find ('all', array ('select' => 'latitude, longitude', 'conditions' => array ('path_id = ?', $this->id)))));
  }
  public function mini_keywords ($length = 50) {
    return mb_strimwidth ($this->keywords, 0, $length, '…','UTF-8');
  }

  public function destroy () {
    if ($this->points)
      foreach ($this->points as $point)
        if (!$point->destroy ())
          return false;

    if ($this->infos)
      foreach ($this->infos as $info)
        if (!$info->destroy ())
          return false;

    if ($this->mappings)
      foreach ($this->mappings as $mapping)
        if (!$mapping->destroy ())
          return false;
  
    return $this->image->cleanAllFiles () && $this->delete ();
  }















  // public function put_image () {
  //   if ($url = $this->picture ('1200x1200', 'server_key'))
  //     return $this->image->put_url ($url);
  //   else
  //     return true;
  // }
  // public function picture ($size = '60x60', $type = 'client_key', $color = 'red') {
  //   $u = round (PathPoint::count (array ('conditions' => array ('path_id = ?', $this->id))) / 50);

  //   $paths = array ();
  //   foreach ($this->points as $i => $point)
  //     if ($i % $u == 0)
  //       array_push ($paths, $point->latitude . ',' . $point->longitude);

  //   return $paths ? 'https://maps.googleapis.com/maps/api/staticmap?path=color:' . $color . '|weight:5|' . implode ('|', $paths) . '&size=' . $size . '&key=' . Cfg::setting ('google', ENVIRONMENT, $type) : '';
  // }
  // public function compute_length () {
  //   $this->CI->load->library ('SphericalGeometry');

  //   return SphericalGeometry::computeLength (array_map (function ($point) {
  //         return new LatLng (
  //             $point->latitude,
  //             $point->longitude
  //           );
  //       }, PathPoint::find ('all', array ('select' => 'latitude, longitude', 'conditions' => array ('path_id = ?', $this->id)))));
  // }
}