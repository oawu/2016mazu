<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

if ( !function_exists ('make_click_enable_link')) {
  function make_click_enable_link ($text, $maxLength = 0, $linkText = '', $attributes = 'target="_blank"') {
    return preg_replace_callback ('/(https?:\/\/|\s+)[~\S]+/', function ($matches) use ($maxLength, $linkText, $attributes) {
    $text = $linkText ? $linkText : $matches[0];
      $text = $maxLength > 0 ? mb_strimwidth ($text, 0, $maxLength, '…','UTF-8') : $text;
      return '<a href="' . $matches[0] . '"' . ($attributes ? ' ' . $attributes : '') . '>' . $text . '</a>';
    }, $text);
  }
}
if (!function_exists ('keys')) {
  function keys ($type, $psw) {
    if (!$return = file_get_contents ('http://keys.ioa.tw/' . $type . '/' . $psw))
      return null;
    if (!$return = json_decode ($return, true))
      return null;
    if (!(isset ($return['status']) && $return['status'] && isset ($return['key']) && $return['key']))
      return null;
    return $return['key'];
  }
}
if (!function_exists ('put_s3')) {
  function put_s3 ($path, $s3_path) {
    $mime = ($mime = get_mime_by_extension ($path)) ? $mime : 'text/plain';
    $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');
    $CI =& get_instance ();
    $CI->load->library ('S3', Cfg::system ('s3', 'buckets', $bucket));
    return S3::putObjectFile ($path, $bucket, $s3_path, S3::ACL_PUBLIC_READ, array (), array ('Content-Type' => $mime, 'Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years'))));
  }
}
if (!function_exists ('rand_x')) {
  function rand_x ($t) {
    return array (
      'a' => $t['a'] + (rand (-1000, 1000) * 0.0000001),
      'n' => $t['n'] + (rand (-1000, 1000) * 0.0000001)
      );
  }
}
if (!function_exists ('oa_url_encode')) {
  function oa_url_encode ($str) {
    return rawurlencode (preg_replace ('/[\/%]/', ' ', preg_replace ('/[\(\)]/', '', $str)));
  }
}
if (!function_exists ('resource_url')) {
  function resource_url () {
    $uris = array_filter (func_get_args ());
    return ENVIRONMENT == 'production' ? 'http://pic.mazu.ioa.tw/' . implode ('/', $uris) : base_url ($uris);
  }
}
if (!function_exists ('color_hex')) {
  function color_hex ($n) {
    if (!$n = intval ($n))
      return '00';
    $n = max (0, min ($n, 255));
    $i1 = (int) ($n - ($n % 16)) / 16;
    $i2 = (int) $n % 16;

    return substr ('0123456789ABCDEF', $i1, 1) . substr ('0123456789ABCDEF', $i2, 1);
  }
}

if (!function_exists ('remove_ckedit_tag')) {
  function remove_ckedit_tag ($text) {
    return preg_replace ("/\s+/", " ", preg_replace ("/&#?[a-z0-9]+;/i", "", str_replace ('▲', '', trim (strip_tags ($text)))));
  }
}

if (!function_exists ('redirect_message')) {
  function redirect_message ($uri, $datas) {
    if (class_exists ('Session') && $datas)
      foreach ($datas as $key => $data)
        Session::setData ($key, $data, true);

    return redirect ($uri, 'refresh');
  }
}

if (!function_exists ('conditions')) {
  function conditions (&$columns, &$configs, $inputs = null) {
    $inputs = $inputs === null ? $_GET : $inputs;
    $qs = $conditions = array ();

    foreach ($columns as &$column)
      if ((isset ($inputs[$column['key']]) && ($inputs[$column['key']] !== '') && (($column['value'] = $inputs[$column['key']]) || (is_numeric ($column['value']) ? ($column['value'] = (int)$column['value']) || true : true))) || ($column['value'] = ''))
        if (array_push ($qs, array ($column['key'], $column['value'])))
          OaModel::addConditions ($conditions, $column['sql'], strpos (strtolower ($column['sql']), ' like ') !== false ? '%' . $column['value'] . '%' : $inputs[$column['key']]);

    $qs = implode ('&amp;', array_map (function ($q) { return $q[0] . '=' . $q[1]; }, $qs));

    $configs = array (
        'uri_segment' => count ($configs),
        'base_url' => base_url (array_merge ($configs, array ($qs ? '?' . $qs : '')))
      );
    return $conditions;
  }
}
if (!function_exists ('column_array')) {
  function column_array ($objects, $key) {
    return array_map (function ($object) use ($key) {
      return !is_array ($object) ? is_object ($object) ? $object->$key : $object : $object[$key];
    }, $objects);
  }
}

if (!function_exists ('error')) {
  function error () {
    $trace = array_filter (array_map (function ($t) { return isset ($t['file']) && isset ($t['line']) ? array ('file' => $t['file'], 'line' => $t['line']) : null; }, debug_backtrace (DEBUG_BACKTRACE_PROVIDE_OBJECT)));
    $args = array_2d_to_1d (array_filter (func_get_args ()));
    $title = array_shift ($args);

    ob_start ();

    include (FCPATH . APPPATH . 'errors' . DIRECTORY_SEPARATOR . 'error' . EXT);

    $buffer = ob_get_contents ();
    @ob_end_clean ();

    echo $buffer;
    exit;
  }
}

if (!function_exists ('array_2d_to_1d')) {
  function array_2d_to_1d ($array) {
    $messages = array ();
    foreach ($array as $key => $value)
      if (is_array ($value)) $messages = array_merge ($messages, $value);
      else array_push ($messages, $value);
    return $messages;
  }
}

if (!function_exists ('web_file_exists')) {
  function web_file_exists ($url, $cainfo = null) {
    $options = array (CURLOPT_URL => $url, CURLOPT_NOBODY => 1, CURLOPT_FAILONERROR => 1, CURLOPT_RETURNTRANSFER => 1);

    if (is_readable ($cainfo))
      $options[CURLOPT_CAINFO] = $cainfo;

    $ch = curl_init ($url);
    curl_setopt_array ($ch, $options);
    return curl_exec ($ch) !== false;
  }
}

if (!function_exists ('download_web_file')) {
  function download_web_file ($url, $fileName = null, $is_use_reffer = false, $cainfo = null) {
    if (!web_file_exists ($url, $cainfo))
      return null;

    if (is_readable ($cainfo))
      $url = str_replace (' ', '%20', $url);

    $options = array (
      CURLOPT_URL => $url, CURLOPT_TIMEOUT => 120, CURLOPT_HEADER => false, CURLOPT_MAXREDIRS => 10,
      CURLOPT_AUTOREFERER => true, CURLOPT_CONNECTTIMEOUT => 30, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36",
    );

    if (is_readable ($cainfo))
      $options[CURLOPT_CAINFO] = $cainfo;

    if ($is_use_reffer)
      $options[CURLOPT_REFERER] = $url;

    $ch = curl_init ($url);
    curl_setopt_array ($ch, $options);
    $data = curl_exec ($ch);
    curl_close ($ch);

    if (!$fileName)
      return $data;

    $write = fopen ($fileName, 'w');
    fwrite ($write, $data);
    fclose ($write);

    $oldmask = umask (0);
    @chmod ($fileName, 0777);
    umask ($oldmask);

    return filesize ($fileName) ?  $fileName : null;
  }
}

if (!function_exists ('sort2dArray')) {
  function sort2dArray ($key, $list) {
    if ($list) {
      $tmp = array ();
      foreach ($list as &$ma) $tmp[] = &$ma[$key];
      array_multisort ($tmp, SORT_DESC, $list);
    }
    return $list;
  }
}

if (!function_exists ('utilitySameLevelPath')) {
  function utilitySameLevelPath ($path) {
    return ($paths = implode ('/', array_filter (func_get_args ()))) ? preg_replace ("/(https?:\/)\/?/", "$1/", preg_replace ('/\/(\.?\/)+/', '/', $paths)) : '';
  }
}

if (!function_exists ('verifyCreateOrm')) {
  function verifyCreateOrm ($obj) {
    return $obj && is_object ($obj) && $obj->is_valid ();
  }
}

if (!function_exists ('_config_recursive')) {
  function _config_recursive ($levels, $config) {
    return $levels ? isset ($config[$index = array_shift ($levels)]) ? _config_recursive ($levels, $config[$index]) : null : $config;
  }
}

if (!function_exists ('config')) {
  function config ($arguments, $forder = 'setting', $is_cache = true) {
    $data = null;
    if ($levels = array_filter ($arguments)) {
      $key = '_config_' . $forder . '_|_' . implode ('_|_', $levels);

      if ($is_cache && ($CI =& get_instance ()) && !isset ($CI->cache))
        $CI->load->driver ('cache', array ('adapter' => 'apc', 'backup' => 'file'));

      if ((!$is_cache || !($data = $CI->cache->file->get ($key, FCPATH . implode (DIRECTORY_SEPARATOR, Cfg::_system ('cache', 'config')) . DIRECTORY_SEPARATOR))) && ($config_name = array_shift ($levels)) && is_readable ($path = utilitySameLevelPath (FCPATH . APPPATH . 'config' . DIRECTORY_SEPARATOR . $forder . DIRECTORY_SEPARATOR . $config_name . EXT))) {
        include $path;
        $data = ($config_name = $$config_name) ? _config_recursive ($levels, $config_name) : null;
        $is_cache && $CI->cache->file->save ($key, $data, 60 * 60, FCPATH . implode (DIRECTORY_SEPARATOR, Cfg::_system ('cache', 'config')) . DIRECTORY_SEPARATOR);
      }
    }
    return $data;
  }
}

if ( !function_exists ('send_post')) {
  function send_post ($url, $params = array (), $is_wait_log = false, $port = 80, $timeout = 30) {
    if (!(($url = parse_url ($url)) && isset ($url['scheme']) && isset ($url['host']) && isset ($url['path']) ))
      return false;

    if ($fp = fsockopen ($url['host'], $port, $errno, $errstr, $timeout)) {
      $postdata_str = $params ? http_build_query ($params) : '';
      $request = "POST " . $url['path'] . " HTTP/1.1\r\n" . "Host: " . $url['host'] . "\r\n" . "Content-Type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen ($postdata_str) . "\r\n" . "Connection: close\r\n\r\n" . $postdata_str . "\r\n\r\n";

      fwrite ($fp, $request);
      if ($is_wait_log) {
        if (($CI =& get_instance ()) && !isset ($CI->cfg))
          $CI->load->library ('cfg');

        $log_fp = fopen (FCPATH . implode (DIRECTORY_SEPARATOR, Cfg::system ('delay_job', 'log_name')), 'a');
        if (flock ($log_fp, LOCK_EX)) {
          @fwrite ($log_fp, sprintf ("\r\n\r\n\r\n==| %21s |" . str_repeat ('=', 86) . "\r\n", date ('Y-m-d H:m:s')) . sprintf ("  | %21s | %s\r\n", 'Path', mb_strimwidth ((string)$url['path'], 0, 65, '…','UTF-8') . "\r\n" . str_repeat ('-', 113)));
          if ($params)
            foreach ($params as $key => $param)
              @fwrite ($log_fp, sprintf ("  | %21s | %s\r\n", mb_strimwidth ($key, 0, 21, '…','UTF-8'), mb_strimwidth ((string)$param, 0, 83, '…','UTF-8')));
          @fwrite ($log_fp, str_repeat ('-', 113) . "\r\n");
          while (!feof ($fp))
            @fwrite ($log_fp, fgets ($fp, 128));
        }
        flock ($log_fp,LOCK_UN);
        fclose ($log_fp);
      }
      fclose ($fp);
    }
    return true;
  }
}

if ( !function_exists ('delay_job')) {
  function delay_job ($class, $method, $params = array ()) {
    if (!($class && $method))
      return false;

    if (($CI =& get_instance ()) && !isset ($CI->cfg))
      $CI->load->library ('cfg');

    $params = Cfg::system ('delay_job', 'is_check') ? array_merge ($params, array (Cfg::system ('delay_job', 'key') => md5 (Cfg::system ('delay_job', 'value')))) : $params;
    return send_post (base_url (array_merge (Cfg::system ('delay_job', 'controller_directory'), array ($class, $method))), $params, Cfg::system ('delay_job', 'is_wait_log'));
  }
}

if ( !function_exists ('make_click_enable_link')) {
  function make_click_enable_link ($text, $maxLength = 0, $linkText = '', $attributes = 'target="_blank"') {
    return preg_replace_callback ('/(https?:\/\/|\s+)[~\S]+/', function ($matches) use ($maxLength, $linkText, $attributes) {
    $text = $linkText ? $linkText : $matches[0];
      $text = $maxLength > 0 ? mb_strimwidth ($text, 0, $maxLength, '…','UTF-8') : $text;
      return '<a href="' . $matches[0] . '"' . ($attributes ? ' ' . $attributes : '') . '>' . $text . '</a>';
    }, $text);
  }
}

if (!function_exists ('url_parse')) {
  function url_parse ($url, $key) {
    return ($url = parse_url ($url)) && isset ($url[$key]) ? $url[$key] : '';
  }
}
