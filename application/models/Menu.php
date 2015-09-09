<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Menu extends OaModel {

  static $table_name = 'menus';

  static $has_one = array (
  );

  static $has_many = array (
    array ('roles', 'class_name' => 'Role', 'through' => 'menu_roles'),
    array ('menu_roles', 'class_name' => 'MenuRole'),
    array ('children', 'class_name' => 'Menu')
  );

  static $belongs_to = array (
    array ('parent', 'class_name' => 'Menu')
  );

  static $targets = array (
    '_self' => '本頁開啟(預設)',
    '_blank' => '新分頁開啟',
    '_parent' => '父層分頁開啟',
    '_top' => '上層視窗開啟',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  // asc 祖父,父親,自己，desc 自己,父親,祖父
  public function ancestry ($order = 'asc') {
    return $this->parent ? strtolower ($order) == 'asc' ? array_merge ($this->parent->ancestry ($order), array ($this)) : array_merge (array ($this), $this->parent->ancestry ($order)) : array ($this);
  }
  public function destroy () {
    if ($this->children)
      foreach ($this->children as $children)
        $children->destroy ();
    return $this->delete ();
  }
}