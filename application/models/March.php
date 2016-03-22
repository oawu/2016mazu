<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class March extends OaModel {

  static $table_name = 'marches';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  private $paths = null;

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function paths ($select = '', $is_GS = true) {
    if ($this->paths !== null) return $this->paths;

    $path_ids = array ();

    if (!isset ($this->id))
      return $path_ids;

    if (!($all_path_sqlite_ids = column_array (MarchPath::find ('all', array ('select' => 'sqlite_id', 'order' => 'sqlite_id DESC', 'conditions' => array (
                                'march_id = ? AND accuracy_horizontal < ?', $this->id, 100
                              ))), 'sqlite_id')))
      return $path_ids;

    $c = count ($all_path_sqlite_ids);
    $unit = $c < 10000 ? $c < 5000 ? $c < 2500 ? $c < 1500 ? $c < 1000 ? $c < 500 ? $c < 200 ? $c < 100 ? $c < 10 ? 0 : 0.01 : 0.05 : 0.15 : 0.3 : 0.46 : 1 : 1.5 : 2.3 : 3;
    for ($i = 0; ($key = $is_GS ? round (($i * (2 + ($i - 1) * $unit)) / 2) : $i) < $all_path_sqlite_ids[0]; $i++)
      if ($temp = array_slice ($all_path_sqlite_ids, $key, 1))
        array_push ($path_ids, array_shift ($temp));

    if (!$path_ids) return $path_ids;

    return $this->paths = MarchPath::find ('all', array ('select' => !$select ? 'id AS i, latitude AS a, longitude AS n, speed as s' : $select, 'order' => 'sqlite_id DESC', 'conditions' => array ('march_id = ? AND sqlite_id IN (?)', $this->id, $path_ids)));
  }
}