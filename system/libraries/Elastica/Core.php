<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Elastica_Core {
  protected $CI     = null;
  protected static $path = null;
  protected static $index = null;
  protected static $client = null;
  protected static $config = array ();

  public function __construct () {
    $this->CI =& get_instance ();

    if (!self::$config)
      self::$config = Cfg::system ('elastica_search');

    if (!(self::$config['is_enabled']))
      return;

    if (self::$path === null)
      array_pop (self::$path = explode (DIRECTORY_SEPARATOR, pathinfo (__FILE__, PATHINFO_DIRNAME)));

    spl_autoload_register (array ('Elastica_Core', '__autoload_elastica'));
  }
  static function __autoload_elastica ($class) {
    if (stripos ($class, 'Elastica') !== FALSE) {
      $path = str_replace ('_', DIRECTORY_SEPARATOR, $class);
      require_once implode (DIRECTORY_SEPARATOR, self::$path) . DIRECTORY_SEPARATOR . $path . EXT;
    }
  }

  protected static function client () {
    if (self::$client)
      return self::$client;

    if (self::$client = new Elastica_Client (array (
        'host' => self::$config['ip'],
        'port' => self::$config['port']
      )))
      return self::$client;

    return null;
  }

  protected static function index () {
    if (self::$index)
      return self::$index;

    if (!($client = self::client ()))
      return null;

    if (self::$index = new Elastica_Index ($client, self::$config['index']))
      return self::$index;

    return null;
  }

  protected static function create ($type, $column, $datas = array ()) {
    if (!($isUse = self::$config['is_enabled']))
      return false;

    if (!($index = self::index ()))
      return false;

    if (!($type = $index->getType ($type)))
      return false;

    $length = count ($datas);
    $limit = self::$config['create_limit'];

    for ($offset = 0; $offset < $length; $offset += $limit)
      if ($data = array_filter (array_map (function ($data) use ($column) { return isset ($data[$column]) ? new Elastica_Document ($data[$column], $data) : null; }, array_slice ($datas, $offset, $limit))))
        $type->addDocuments ($data);

    return !$type->getIndex ()
                 ->refresh ()
                 ->hasError ();
  }

  protected static function find ($type, $option = array ()) {
    if (!($isUse = self::$config['is_enabled']))
      return array ();

    if (!($index = self::index ()))
      return array ();

    try {
      self::_modify_option ($option);
      $query = self::_build_query ($option);

      $search = new Elastica_Search (self::client ());
      $result = $search->addIndex (self::index ())
                       ->addType ($type)
                       ->search ($query);

      return array_filter (array_map (function ($result) use ($option) {
                return array_filter (array_map (function ($t) use ($option) { return $option['select'] ? $t[0] : $t; }, $result->getData ()));
              }, $result->getResults ()));
    } catch (Exception $e) {
      return array ();
    }
  }

  protected static function destroy ($type, $ids) {
    if (!($isUse = self::$config['is_enabled']))
      return false;

    if (!($index = self::index ()))
      return false;

    return !$index->getType ($type)
                  ->deleteIds ($ids)
                  ->hasError ();
  }

  protected static function count ($type, $option = array ()) {
    if (!($isUse = self::$config['is_enabled']))
      return 0;

    if (!($index = self::index ()))
      return 0;

    try {
      self::_modify_option ($option);
      $query = self::_build_query ($option);

      return $index->getType ($type)
                   ->count ($query);

    } catch (Exception $e) {
      return 0;
    }
  }

  protected static function deleteIndex () {
    if (!($isUse = self::$config['is_enabled']))
      return false;

    if (!($index = self::index ()))
      return false;

    if (!$index->exists ())
      return false;

    return !$index->delete ()->hasError ();
  }

  protected static function deleteType ($type) {
    if (!($isUse = self::$config['is_enabled']))
      return false;

    if (!($index = self::index ()))
      return false;

    if (!$index->exists ())
      return false;

    if (!($type = $index->getType ($type)))
      return false;

    return !$type->delete ()
                 ->hasError ();
  }

  private static function _modify_option (&$option) {
    $option['must']          = isset ($option['must'])          ? array_filter ($option['must']) : array ();
    $option['limit']         = isset ($option['limit'])         ? $option['limit'] : 100;
    $option['range']         = isset ($option['range'])         ? array_filter ($option['range']) : array ();
    $option['offset']        = isset ($option['offset'])        ? $option['offset'] : 0;
    $option['select']        = isset ($option['select'])        ? array_filter ($option['select']) : array ();
    $option['should']        = isset ($option['should'])        ? array_filter ($option['should']) : array ();
    $option['must_not']      = isset ($option['must_not'])      ? array_filter ($option['must_not']) : array ();
    $option['script_fields'] = isset ($option['script_fields']) ? array_filter ($option['script_fields']) : array ();

    $sort = array ();
    if (isset ($option['sort']))
      foreach ($option['sort'] as $key => $order)
        $sort[$key] = array ('order' => strtolower ($order));

    $option['sort'] = $sort;
  }
  private static function _build_query ($option) {
    $bool = null;

    if ($option['must'])
      foreach ($option['must'] as $field => $values)
        if ($values = !is_array ($values) ? array ($values) : $values)
          foreach ($values as $value) {
            $bool = !$bool ? new Elastica_Query_Bool () : $bool;
            $text = new Elastica_Query_Match ();
            $text->setMatch ($field, $value);
            $bool->addMust ($text);
          }

    if ($option['must_not'])
      foreach ($option['must_not'] as $field => $values)
        if ($values = !is_array ($values) ? array ($values) : $values)
          foreach ($values as $value) {
            $bool = !$bool ? new Elastica_Query_Bool () : $bool;
            $text = new Elastica_Query_Match ();
            $text->setMatch ($field, $value);
            $bool->addMustNot ($text);
          }

    if ($option['should'])
      foreach ($option['should'] as $field => $values)
        if ($values = !is_array ($values) ? array ($values) : $values)
          foreach ($values as $value) {
            $bool = !$bool ? new Elastica_Query_Bool () : $bool;
            $text = new Elastica_Query_Match ();
            $text->setMatch ($field, $value);
            $bool->addShould ($text);
          }

    if ($option['range'])
      foreach ($option['range'] as $field => $values) {
          $bool = !$bool ? new Elastica_Query_Bool () : $bool;
          $text = new Elastica_Query_Range ();
          $text->addField ($field, $values);
          $bool->addMust ($text);
      }

    $query = $bool ? new Elastica_Query ($bool) : new Elastica_Query ();

    if ($option['sort'])
      $query->setSort ($option['sort']);

    if ($option['offset'])
      $query->setFrom ($option['offset']);

    if ($option['select'])
      $query->setFields ($option['select']);

    if ($option['limit'] > 0)
      $query->setSize ($option['limit']);

    if ($option['script_fields'])
      foreach ($option['script_fields'] as $name => $script_field) {
        if (!(isset ($script_field['script']) && $script_field['script'])) continue;

        $script = new Elastica_Script ($script_field['script']);

        if (isset ($script_field['params']) && $script_field['params'])
          $script->setParams ($script_field['params']);

        $query->addScriptField ($name, $script);
      }

    return $query;
  }
}
