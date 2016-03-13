<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class StoreTag extends OaModel {

  static $table_name = 'store_tags';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'StoreTagMapping', 'order' => 'store_id DESC'),
    array ('stores', 'class_name' => 'Store', 'through' => 'mappings')
  );

  static $belongs_to = array (
  );

  const NO_ON_SITE_NAMES = 0;
  const IS_ON_SITE_NAMES = 1;

  static $isOnSiteNames = array(
    self::NO_ON_SITE_NAMES => '隱藏',
    self::IS_ON_SITE_NAMES => '顯示',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function destroy () {
    if ($this->mappings)
      foreach ($this->mappings as $mapping)
        if (!$mapping->destroy ())
          return false;

    return $this->delete ();
  }
}