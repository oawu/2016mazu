<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

include_once 'Core.php';

class ElasticaSearch extends Elastica_Core {
  static $type_name = '';
  static $primary_key = null;
  private $columns = array ();
  private $notColumns = array ();

  public function __construct ($data = array ()) {
    parent::__construct ();

    $this->columns = $data;
    spl_autoload_register (array ('ElasticaSearch', '__autoload_search'));
  }

  public static function __autoload_search ($class) {
    if (!class_exists ($class) && preg_match ("/" . parent::$config['class_suffix'] . "$/", $class) && is_readable ($file = implode (DIRECTORY_SEPARATOR, array_merge (array (FCPATH), parent::$config['class_directory'], array ($class . EXT)))))
      require_once $file;
  }
  public function __get ($column) {
    if (isset ($this->columns[$column]))
      return $this->columns[$column];

    if ($column === 'fields')
      return $this->columns;

    return null;
  }

  public function __set ($column, $value) {
    if (isset ($this->columns[$column]))
      if ($column == self::primaryKey ())
        return $this->columns[$column];
      else
        return $this->columns[$column] = $value;
    else
      return $this->notColumns[$column] = $value;
  }

  public static function primaryKey () {
    if (self::$primary_key)
      return self::$primary_key;

    self::$primary_key = static::$primary_key;

    if (!self::$primary_key)
      self::$primary_key = 'id';

    return self::$primary_key;
  }

  public static function typeName () {
    if (self::$type_name)
      return self::$type_name;

    self::$type_name = static::$type_name;

    if (!self::$type_name)
      self::$type_name = get_called_class ();

    return self::$type_name;
  }

  public static function create ($data = array ()) {
    if (parent::create (self::typeName (), self::primaryKey (), array ($data)))
      return self::find ('one', array (
          'must' => array (self::primaryKey () => $data[self::primaryKey ()])
        ));
    return null;
  }

  public static function createMany ($datas = array ()) {
    if (parent::create (self::typeName (), self::primaryKey (), $datas))
      return array_filter (array_map (function ($data) {
              return ($obj = self::find ('all', array (
                              'must' => array (self::primaryKey () => $data[self::primaryKey ()])
                            ))) ? $obj : null;
            }, $datas));
    return array ();
  }

  public static function find ($unit, $option = array ()) {
    if ($unit == 'one')
      return ($datas = parent::find (self::typeName (), array_merge ($option, array ('limit' => 1, 'offset' => 0)))) ? new self ($datas[0]) : null;
    else
      return array_map (function ($data) { return new self ($data); }, parent::find (self::typeName (), $option));
  }

  public function save () {
    return self::update ($this->fields);
  }

  public static function update ($data = array ()) {
    if (!isset ($data[self::primaryKey ()]))
      return null;

    if (!($ori = self::find ('one', array ('must' => array (self::primaryKey () => $data[self::primaryKey ()])))))
      return null;

    $columns = array_merge ($ori->fields, $data);

    if ($newColumns = array_diff (array_keys ($data), array_keys ($ori->fields)))
      foreach ($newColumns as $newColumn)
        unset ($columns[$newColumn]);

    return self::create ($columns);
  }

  public static function updateMany ($datas = array ()) {
    if (!($datas = array_filter ($datas, function ($data) { return isset ($data[self::primaryKey ()]); })))
      return array ();

    $columnsList = array_filter (array_map (function ($data) {
      if (!($ori = self::find ('one', array ('must' => array (self::primaryKey () => $data[self::primaryKey ()])))))
        return null;

      $columns = array_merge ($ori->fields, $data);

      if ($newColumns = array_diff (array_keys ($data), array_keys ($ori->fields)))
        foreach ($newColumns as $newColumn)
          unset ($columns[$newColumn]);

      return $columns;
    }, $datas));

    return self::createMany ($columnsList);
  }

  public function delete () {
    if (!isset ($this->fields[self::primaryKey ()]))
      return false;
    return self::destroy (array ($this->fields[self::primaryKey ()]));
  }

  public static function deleteMany ($ids) {
    return self::destroy ($ids);
  }

  protected static function destroy ($ids = array ()) {
    return $ids ? parent::destroy (self::typeName (), $ids) : true;
  }

  public static function clean () {
    return self::deleteType (self::$type_name);
  }

  public static function count ($option = array ()) {
    return parent::count (self::typeName (), $option);
  }

  protected static function deleteType ($type) {
    return parent::deleteType ($type);
  }

  public static function deleteIndex () {
    return parent::deleteIndex ();

  }
}
