<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Role extends OaModel {

  static $table_name = 'roles';

  static $has_one = array (
  );

  static $has_many = array (
    array ('user_roles', 'class_name' => 'UserRole'),
    array ('menu_roles', 'class_name' => 'MenuRole')
  );

  static $belongs_to = array (
  );

  private static $const_ids = array (
      'root' => 1,
      'admin' => 2,
      'login' => 3,
      'guest' => 4,
      'black' => 5,
    );

  private $users = null;
  private $menus = null;

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public static function const_ids ($key = null) {
    return $key && isset (self::$const_ids[$key]) ? self::$const_ids[$key] : (!$key ? self::$const_ids : array ());
  }
  public function users () {
    if ($this->users !== null)
      return $this->users;

    if (!($user_ids = column_array ($this->user_roles, 'user_id')))
      return $this->users = array ();

    return $this->users = User::find ('all', array ('conditions' => array ('id IN (?)', $user_ids)));
  }

  public function menus () {
    if ($this->menus !== null)
      return $this->menus;

    if (!($menu_ids = column_array ($this->menu_roles, 'menu_id')))
      return $this->menus = array ();

    return $this->menus = Menu::find ('all', array ('conditions' => array ('id IN (?)', $menu_ids)));
  }

  public function destroy () {
    if ($this->user_roles)
      foreach ($this->user_roles as $user_role)
        $user_role->destroy ();

    if ($this->menu_roles)
      foreach ($this->menu_roles as $menu_role)
        $menu_role->destroy ();

    return $this->delete ();
  }
}