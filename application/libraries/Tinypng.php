<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Tinypng {
  private static $path = null;
  private static $data = null;
  
  private static function writeFile ($fileName, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE) {
    if (!$write = @fopen ($fileName, $mode))
      return false;

    fwrite ($write, $data); fclose ($write);
    $oldmask = umask (0); @chmod ($fileName, 0777); umask ($oldmask);

    return true;
  }

  private static function isDatetime ($date) {
    return (DateTime::createFromFormat('Y-m-d H:i:s', $date) !== false);
  }

  private static function path () {
    if (self::$path !== null) return self::$path;
    return self::$path = Cfg::setting ('tinypng', 'keys_realpath');
  }
  private static function data () {
    if (self::$data !== null) return self::$data;
    return self::$data = include_once self::path ();
  }
  private static function setData ($data) {
    if (!(isset ($data['updated_at']) && self::isDatetime ($data['updated_at']) && isset ($data['keys'])))
      return self::data ();

    $str = "<?php\n";
    $str .= "return array (\n";
    $str .= "    'updated_at' => '" . $data['updated_at'] . "',\n";
    $str .= "    'keys' => array (\n";
    foreach ($data['keys'] as $key => $quota)
      $str .= "      '" . $key . "' => " . $quota . ",\n";
    $str .= "    )\n";
    $str .= "  );\n";

    if (self::writeFile (self::path (), $str))
      self::$data = $data;

    return self::data ();
  }

  public static function checkNewMonth () {
    $data = self::data ();

    if (!self::isDatetime ($data['updated_at']))
      $data['updated_at'] = date ('Y-m-d H:i:s');
    else if (DateTime::createFromFormat ('Y-m-d H:i:s', $data['updated_at'])->format ('Y-m') < date ('Y-m'))
      $data = array (
          'updated_at' => date ('Y-m-d H:i:s'),
          'keys' => array_combine (array_keys ($data['keys']), array_map (function () { return 500; }, $data['keys']))
        );

    return self::setData ($data);
  }

  public static function key () {
    self::checkNewMonth ();

    $data = self::data ();

    $return = array ();
    foreach ($data['keys'] as $key => $quota)
      if (($quota > 0) && ($return = array ('key' => $key, 'quota' => $quota)))
        break;

    return $return;
  }
  public static function updateQuota ($key, $quota = 0) {
    if (!$key)
      return false;

    if (is_array ($key) && isset ($key['key']) && isset ($key['quota'])) {
      $quota = $key['quota'];
      $key = $key['key'];
    }

    $data = self::data ();
    $data['updated_at'] = date ('Y-m-d H:i:s');

    if (isset ($data['keys'][$key]))
      $data['keys'][$key] = $quota;

    return self::setData ($data);
  }
}