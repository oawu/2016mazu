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
    if (!($posts = self::ci ()->input->post ()))
      return array ();

    $posts = $xss_clean ? array_map (function ($post) { return self::ci ()->security->xss_clean ($post);}, $posts) : $posts;

    if ($index === null)
      return $posts;

    if (!isset ($posts[$index]))
      return null;

    return $posts[$index];
  }

  public static function get ($index = null, $xss_clean = true) {
    if (!($gets = self::ci ()->input->get ()))
      return array ();

    $gets = $xss_clean ? array_map (function ($get) { return self::ci ()->security->xss_clean ($get);}, $gets) : $gets;

    if ($index === null)
      return $gets;

    if (!isset ($gets[$index]))
      return null;

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