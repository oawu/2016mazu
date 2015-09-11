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
    array ('children', 'class_name' => 'Menu', 'include' => array ('children'))
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
  private $roles = null;

  static $struct = array (
    );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public function roles () {
    if ($this->roles !== null)
      return $this->roles;

    if (!($role_ids = column_array ($this->menu_roles, 'role_id')))
      return $this->roles = array ();

    return $this->roles = Role::find ('all', array ('conditions' => array ('id IN (?)', $role_ids)));
  }

  public function role_names () {
    return column_array ($this->roles (), 'name');
  }

  public function struct ($roles = array ()) {
    sort ($roles = is_string ($roles) ? array ($roles) : array_map (function ($role) { return is_object ($role) ? $role->name : $role; }, $roles));

    $static_key = $this->id . '|' . implode ('_', $roles);

    if (isset (Menu::$struct[$static_key]))
      return Menu::$struct[$static_key];

    if (!($roles && $this->role_names () && array_intersect ($this->role_names (), $roles)))
      return Menu::$struct[$static_key] = null;

    $menu = array ();
      foreach ($this->table()->columns as $key => $column)
        $menu[$key] = (string)$this->$key;

    $menu['roles'] = array_map (function ($role) {
      return array ('name' => $role->name, 'description' => $role->description);
    }, $this->roles ());

    $menu['children'] = array_filter (array_map (function ($child) use ($roles) {
          return $child->struct ($roles);
        }, $this->children));

    return Menu::$struct[$static_key] = $menu;
  }
  public static function structs ($roles = array (), $option = array ('conditions' => array ('menu_id IS NULL'))) {
    sort ($roles = is_string ($roles) ? array ($roles) : array_map (function ($role) { return is_object ($role) ? $role->name : $role; }, $roles));

    $menus = self::all ($option);
    $static_key = implode ('_', column_array ($menus, 'id')) . '|' . implode ('_', $roles);

    if (isset (Menu::$struct[$static_key]))
      return Menu::$struct[$static_key];

    return Menu::$struct[$static_key] = array_filter (array_map (function ($menu) use ($roles) {
          return $menu->struct ($roles);
        }, $menus));
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