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
    array ('roles', 'class_name' => 'MenuRole', 'order' => 'id ASC'),
    array ('children', 'class_name' => 'Menu', 'order' => 'sort ASC', 'include' => array ('children'))
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

  static $struct = array (
    );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public function roles () {
    return column_array ($this->roles, 'role');
  }
  public function destroy () {
    if ($this->children)
      foreach ($this->children as $children)
        $children->destroy ();

    if ($this->roles)
      foreach ($this->roles as $menu_role)
        $menu_role->destroy ();

    return $this->delete ();
  }

  // asc 祖父,父親,自己，desc 自己,父親,祖父
  public function ancestry ($order = 'asc') {
    return $this->parent ? strtolower ($order) == 'asc' ? array_merge ($this->parent->ancestry ($order), array ($this)) : array_merge (array ($this), $this->parent->ancestry ($order)) : array ($this);
  }

  public function struct ($roles = array ()) {
    $static_key = $this->id . '|' . implode ('_', $roles);

    if (isset (Menu::$struct[$static_key]))
      return Menu::$struct[$static_key];

    if (!($roles && $this->roles () && ($roles = array_intersect ($this->roles (), $roles))))
      return Menu::$struct[$static_key] = null;

    $menu = array ();
      foreach ($this->table()->columns as $key => $column)
        $menu[$key] = (string)$this->$key;

    $role_infos = Cfg::setting ('role');

    $menu['roles'] = array_combine ($roles, array_map (function ($role) use ($role_infos) {
              return array_merge (array ('key' => $role), $role_infos[$role]);
            }, $roles));

    $menu['children'] = array_filter (array_map (function ($child) use ($roles) {
          return $child->struct ($roles);
        }, $this->children));

    return Menu::$struct[$static_key] = $menu;
  }
  public static function structs ($roles = array (), $option = array ('order' => 'sort ASC', 'conditions' => array ('menu_id IS NULL'))) {

    $menus = self::all ($option);
    $static_key = implode ('_', column_array ($menus, 'id')) . '|' . implode ('_', $roles);

    if (isset (Menu::$struct[$static_key]))
      return Menu::$struct[$static_key];

    return Menu::$struct[$static_key] = array_filter (array_map (function ($menu) use ($roles) {
          return $menu->struct ($roles);
        }, $menus));
  }

}