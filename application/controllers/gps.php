<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Gps extends Site_controller {

  public function path () {
    if (!(($id = OAInput::get ('id')) && ($path = Path::find_by_id ($id))))
      return $this->output_json (array ('m' => array ()));

    $points = array_map (function ($point) {
      return array (
          'a' => $point->lat,
          'n' => $point->lng,
        );
    }, $path->mini_points ('', false));
    
    return $this->output_json (array ('m' => $points));
  }
  public function index () {
    $march19 = '2016-04-25 00:00:00';

    $temp = new DateTime ($march19);
    $day_count = $temp->diff (new DateTime (date ('Y-m-d H:i:s')))->format ('%a');
    $day_count = strtotime ($march19) - strtotime (date ('Y-m-d H:i:s')) < 0 ? 0 - $day_count : $day_count;

    if (ENVIRONMENT == 'production' && $day_count > 0)
      redirect (base_url ('maps', 'dintao'));

    $this->set_subtitle ('三月十九，神轎定位')
         ->add_css (base_url ('application', 'views', 'content', 'site', 'maps', 'gps', 'a.css'))
         ->add_js (Cfg::setting ('google', 'client_js_url'), false)
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => '_url_load_path', 'value' => base_url ('gps', 'path')))
         ->add_hidden (array ('id' => '_url_set_location', 'value' => base_url ('api', 'march_users')))
         ->add_hidden (array ('id' => '_url_report', 'value' => base_url ('api', 'march_messages', 'report')))
         ->add_hidden (array ('id' => '_url_send_message', 'value' => base_url ('api', 'march_messages')))
         ->load_view ();
  }
}
