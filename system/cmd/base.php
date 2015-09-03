<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

  date_default_timezone_set ('Asia/Taipei');

  define ('EXT', '.php');
  define ('SELF', pathinfo (__FILE__, PATHINFO_BASENAME));

  $path = explode (DIRECTORY_SEPARATOR, dirname (str_replace (SELF, '', __FILE__)));
  array_pop($path);

  define ('FCPATH', implode (DIRECTORY_SEPARATOR, $path) . '/');

  define ('APPPATH', FCPATH . 'application/');
  define ('BASEPATH', FCPATH . 'system/');

  define ('ENVIRONMENT', 'console');

