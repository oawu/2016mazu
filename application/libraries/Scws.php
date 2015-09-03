<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Scws {
  function __construct () {
  }

  private static function _inArrayLike ($referencia, $array) {
    if (!$array)
      return false;

    foreach ($array as $ref)
      if (($referencia !== $ref) && strstr ($ref, $referencia))
        return true; 

    return false; 
  }
  public static function explode ($str, $limit = 10, $idf = 5, $respond = 'json', $charset = 'utf8', $ignore = true, $duality = false, $traditional = true, $multi = 0x03) {
    $CI =& get_instance ();
    $url = 'http://www.xunsearch.com/scws/api.php';

    $options = array (
      CURLOPT_URL => $url, CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query (array (
        'data' => $str,
        'respond' => $respond,
        'charset' => $charset,
        'ignore' => $ignore ? 'yes' : 'no',
        'duality' => $duality ? 'yes' : 'no',
        'traditional' => ($charset == 'utf8') && $traditional ? 'yes' : 'no',
        'multi' => $multi
        )),
      CURLOPT_TIMEOUT => 120, CURLOPT_HEADER => false, CURLOPT_MAXREDIRS => 10,
      CURLOPT_AUTOREFERER => true, CURLOPT_CONNECTTIMEOUT => 30, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36",
    );

    $ch = curl_init ($url);
    curl_setopt_array ($ch, $options);
    $data = curl_exec ($ch);
    curl_close ($ch);
    
    $data = json_decode ($data, true);

    if (!(isset ($data['status']) && $data['status'] && ($data['status'] === 'ok') && ($data = $data['words'])))
      return array ();

    $data = array_filter ($data, function ($t) use ($idf) {
      return $t['idf'] > $idf;
    });

    usort ($data, function ($a, $b) {
      return $a['idf'] < $b['idf'];
    });

    $data = array_unique (array_map (function ($t) {
          return $t['word'];
        }, $data));

    $data = array_filter (array_map (function ($t) use ($data) {
          return self::_inArrayLike ($t, $data) ? null : $t;
        }, array_slice ($data, 0, $limit * 20)));

    return array_slice ($data, 0, $limit);
  }
}
