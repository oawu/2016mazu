<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Path extends OaModel {

  static $table_name = 'paths';

  static $has_one = array (
  );

  static $has_many = array (
    array ('points', 'class_name' => 'PathPoint'),
    array ('infos', 'class_name' => 'PathInfo'),
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('image', 'PathImageImageUploader');
  }

  public function put_image () {
    if ($url = $this->picture ('1200x1200', 'server_key'))
      return $this->image->put_url ($url);
    else
      return true;
  }
  public function picture ($size = '60x60', $type = 'client_key', $color = 'red') {
    $u = round (PathPoint::count (array ('conditions' => array ('path_id = ?', $this->id))) / 50);

    $paths = array ();
    foreach ($this->points as $i => $point)
      if ($i % $u == 0)
        array_push ($paths, $point->latitude . ',' . $point->longitude);

    return $paths ? 'https://maps.googleapis.com/maps/api/staticmap?path=color:' . $color . '|weight:5|' . implode ('|', $paths) . '&size=' . $size . '&key=' . Cfg::setting ('google', ENVIRONMENT, $type) : '';
  }
  public function compute_length () {
    $this->CI->load->library ('SphericalGeometry');

    return SphericalGeometry::computeLength (array_map (function ($point) {
          return new LatLng (
              $point->latitude,
              $point->longitude
            );
        }, PathPoint::find ('all', array ('select' => 'latitude, longitude', 'conditions' => array ('path_id = ?', $this->id)))));
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
  
    return $this->image->cleanAllFiles () && $this->delete ();
  }
}