<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class March19 extends Site_controller {

  public function x () {
    $get_place_ids = function ($name, $types, $lat, $lng, $radius = 1000) {
      $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' . $lat . ',' . $lng . '&radius=' . $radius . '&types=' . $types . '&name=' . urlencode ($name) . '&key=AIzaSyAO36Pj983f8mset--MbWdjFQOAjiILhaE&language=zh-TW';

      $resp_json = file_get_contents ($url);
      $result = json_decode ($resp_json, true);

      return isset ($result['results']) && $result['results'] && is_array ($result['results']) ? array_filter (array_map( function ($a) {
            return isset ($a['place_id']) ? $a['place_id'] : null;
          }, $result['results'])) : array ();
    };

    $get_place_details = function ($place_id) {
      $url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $place_id . '&language=zh-TW&key=AIzaSyAO36Pj983f8mset--MbWdjFQOAjiILhaE';
      $resp_json = file_get_contents ($url);
      $result = json_decode ($resp_json, true);

      if (!(isset ($result['result']) &&
            isset ($result['result']['place_id']) &&
            isset ($result['result']['formatted_address']) &&
            isset ($result['result']['formatted_phone_number']) &&
            isset ($result['result']['geometry']['location']['lat']) &&
            isset ($result['result']['geometry']['location']['lng']) && 
            isset ($result['result']['name']) && 
            isset ($result['result']['opening_hours']['weekday_text'])))
        return null;

      return $result['result'];
    };

    $get_places_details = function ($place_ids) use ($get_place_details) {
      return array_filter (array_map ($get_place_details, $place_ids));
    };

    $place_ids = $get_place_ids ('麥當勞', 'food', 25.04936914838976, 121.53831481933594, 1000);
    
    $places_details = $get_places_details ($place_ids);
    
    // echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    // var_dump ($places_details);
    // exit ();

    $this

        ->set_title ('')
        ->add_js (Cfg::setting ('google', 'client_js_url'), false)
        ->load_view (array (
            'center' => array (
              'lat' => 25.04936914838976, 
              'lng' => 121.53831481933594
              ),
            'places_details' => $places_details
          ));
  }
  public function about ($index = '活動簡介') {
    $index = trim (urldecode ($index));

    $this->add_tab ('活動簡介', array ('href' => base_url ($this->get_class (), 'about', '簡介'), 'index' => '簡介'))
         ->add_tab ('活動時間', array ('href' => base_url ($this->get_class (), 'about', '時間'), 'index' => '時間'))
         ->add_tab ('路關細節', array ('href' => base_url ($this->get_class (), 'about', '路關'), 'index' => '路關'))
         ->add_tab ('參與陣頭', array ('href' => base_url ($this->get_class (), 'about', '陣頭'), 'index' => '陣頭'))
         ->add_tab ('注意事項', array ('href' => base_url ($this->get_class (), 'about', '注意事項'), 'index' => '注意事項'))
         ;

    $this->set_tab_index ($index)
         ->set_subtitle ('路關總覽')
         ->load_view ();
  }
}
