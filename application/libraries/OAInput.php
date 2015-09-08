<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class OAInput {
  private static $ci = null;

  public function __construct () {
  }

  public static function ci () {
    if (self::$ci !== null)
      return self::$ci;

    self::$ci =& get_instance ();

    return self::$ci;
  }

  public static function post ($index = null, $xss_clean = true) {
    $posts = self::ci ()->input->post ();

    if ($index === null)
      return $posts;

    if (!isset ($posts[$index]))
      return null;

    if ($xss_clean)
      return self::ci ()->security->xss_clean ($posts[$index]);
    else
      return $posts[$index];
  }

  public static function get ($index = null, $xss_clean = true) {
    $gets = self::ci ()->input->get ();

    if ($index === null)
      return $gets;

    if (!isset ($gets[$index]))
      return null;

    if ($xss_clean)
      return self::ci ()->security->xss_clean ($gets[$index]);
    else
      return $gets[$index];
  }

  public static function file ($index = null) {
    if (!$_FILES)
      return array ();

    if (!function_exists ('get_upload_file') || !function_exists ('transposed_all_files_array'))
      self::ci ()->load->helper ('oa');

    if ($index === null)
      return transposed_all_files_array ($_FILES);

    preg_match_all ('/^(?P<var>\w+)(\s?\[\s?\]\s?)$/', $index, $matches);

    if ($matches = $matches['var'] ? $matches['var'][0] : null)
      return get_upload_file ($matches);
    else
      return get_upload_file ($index, 'one');
  }
}