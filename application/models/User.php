<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class User extends OaModel {

  static $table_name = 'users';

  static $has_one = array (
  );

  static $has_many = array (
    array ('user_roles', 'class_name' => 'UserRole')
  );

  static $belongs_to = array (
  );

  private static $current = '';
  private $roles = null;

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public function roles () {
    if ($this->roles !== null)
      return $this->roles;

    if (!($role_ids = column_array ($this->user_roles, 'role_id')))
      return $this->roles = array ();

    return $this->roles = Role::find ('all', array ('conditions' => array ('id IN (?)', $role_ids)));
  }

  public function role_names () {
    return column_array ($this->roles (), 'name');
  }

  public static function current () {
      if (self::$current !== '')
        return self::$current;

      if ($id = Session::getData ('user_id'))
        return self::$current = User::find_by_id ($id);
      else
        return self::$current = null;
  }

  public function avatar ($w = 100, $h = 100) {
    $size = array ();
    array_push ($size, isset ($w) && $w ? 'width=' . $w : '');
    array_push ($size, isset ($h) && $h ? 'height=' . $h : '');

    return 'https://graph.facebook.com/' . $this->uid . '/picture' . (($size = implode ('&', array_filter ($size))) ? '?' . $size : '');
  }
}