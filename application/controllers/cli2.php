<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Cli2 extends Site_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }

  public function baishatun_showtaiwan_1 () {
    $url = 'http://showtaiwan.hinet.net/event/201505A/get_current_location.php?_=1432428335943';

    if (!($get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url))))) {
      BaishatunErrorLog::create (array ('message' => '[showtaiwan 1] 取不到原始碼！'));
      return false;
    }

    if (!$objs = json_decode ($get_html_str, true)) {
      BaishatunErrorLog::create (array ('message' => '[showtaiwan 1] 沒有陣列！'));
      return false;
    }

    foreach ($objs as $obj)
      if (!verifyCreateOrm ($path = BaishatunShowtaiwan1Path::create (array (
                  'lat' => $obj['y'],
                  'lng' => $obj['x'],
                  'lat2' => $obj['y'] + (rand (-1000, 1000) * 0.00000001),
                  'lng2' => $obj['x'] + (rand (-1000, 1000) * 0.00000001),
                  'address' => $obj['addr'],
                  'target' => $obj['target'],
                  'distance' => $obj['distance'],
                  'time_at' => $obj['year'] . '-' . $obj['month'] . '-' . $obj['day'] . ' ' . $obj['hour'] . ':' . $obj['min'] . ':' . '00',
                ))))
        BaishatunErrorLog::create (array (
            'message' => '[showtaiwan 1] 新增錯誤！'
          ));
    return true;
  }
  public function baishatun_showtaiwan_2 () {
    $url = 'http://showtaiwan.hinet.net/event/201505A/links/data/get_current_location.php';
    
    if (!($get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url))))) {
      BaishatunErrorLog::create (array ('message' => '[showtaiwan 2] 取不到原始碼！'));
      return false;
    }

    if (!$obj = json_decode ($get_html_str, true)) {
      BaishatunErrorLog::create (array ('message' => '[showtaiwan 2] 沒有陣列！'));
      return false;
    }

    if (!verifyCreateOrm ($path = BaishatunShowtaiwan2Path::create (array (
                'lat' => $obj['y'],
                'lng' => $obj['x'],
                'lat2' => $obj['y'] + (rand (-1000, 1000) * 0.00000001),
                'lng2' => $obj['x'] + (rand (-1000, 1000) * 0.00000001),
                'address' => $obj['addr'],
                'target' => $obj['target'],
                'distance' => $obj['distance'],
                'time_at' => '2015' . '-' . $obj['month'] . '-' . $obj['day'] . ' ' . $obj['hour'] . ':' . $obj['min'] . ':' . '00',
              ))))
      BaishatunErrorLog::create (array ('message' => '[showtaiwan 2] 新增錯誤！'));
    return true;
  }
  public function baishatun_com () {
    $this->load->library ('phpQuery');
    $url = 'http://i.bamboocat.net/gps/';

    if (!($get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url))))) {
      BaishatunErrorLog::create (array ('message' => '[baishatun com] 取不到原始碼！'));
      return false; 
    }

    preg_match_all ('/addMarker\s*\((?P<lat>.*)\s*,\s*(?P<lng>.*)\);/', $get_html_str, $result);
    if (!($result['lat'] && $result['lng']&& $result['lat'][0] && $result['lng'][0])) {
      BaishatunErrorLog::create (array ('message' => '[baishatun com] 網頁內容有誤！'));
      return false; 
    }

    if (!verifyCreateOrm ($path = BaishatunComPath::create (array (
                'lat' => $result['lat'][0],
                'lng' => $result['lng'][0],
                'lat2' => $result['lat'][0] + (rand (-19999, 19999) * 0.00000001),
                'lng2' => $result['lng'][0] + (rand (-19999, 19999) * 0.00000001),
                'address' => '',
                'target' => '',
                'distance' => '',
                'time_at' => date ('Y-m-d H:i:s'),
              ))))
      return BaishatunErrorLog::create (array ('message' => '[baishatun com] 新增錯誤！'));
  }
  public function clean_baishatun_cell () {
    clean_cell ('baishatun_cell', '*');
  }
  public function baishatun () {
    for ($i = 1; $i < 4; $i++) { 
      try {
        $this->baishatun_showtaiwan ($i);
      }catch(Exception $e) { BaishatunErrorLog::create (array ('message' => '[baishatun crontab ' . $i . '] 執行錯誤！')); }
    }
  }
  public function baishatun_showtaiwan ($id = 0) {
    switch ($id) {
      default:
      case '1':
        if ($this->baishatun_showtaiwan_1 ())
          BaishatunErrorLog::create (array ('message' => '[showtaiwan 1] 執行錯誤！'));
        break;
      
      case '2':
        if ($this->baishatun_showtaiwan_2 ())
          BaishatunErrorLog::create (array ('message' => '[showtaiwan 2] 執行錯誤！'));
        break;
      
      case '3':
        if ($this->baishatun_com ())
          BaishatunErrorLog::create (array ('message' => '[baishatun com] 執行錯誤！'));
        break;
    }
    $this->clean_baishatun_cell ();
  }
}
