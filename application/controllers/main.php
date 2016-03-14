<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Main extends Site_controller {

  // public function x ($msgs = array ()) {
  //   if (!$msgs)
  //     $msgs = array (
  //         '錯誤原因' => '不明原因錯誤！',
  //         '錯誤時間' => date ('Y-m-d H:i:s'),
  //       );
    
  //   $html = "<article style='font-size:15px;line-height:22px;color:rgb(85,85,85)'><p style='margin-bottom:0'>Hi 管理員,</p><section style='padding:5px 20px'><p>剛剛發生了系統異常的狀況，以下是錯誤訊息：</p><table style='width:100%;border-collapse:collapse'><tbody>";
  //   foreach ($msgs as $title => $msg)
  //     $html .= "<tr><th style='width:100px;text-align:right;padding:11px 5px 10px 0;border-bottom:1px dashed rgba(200,200,200,1)'>" . $title . "：</th><td style='text-align:left;text-align:left;padding:11px 0 10px 5px;border-bottom:1px dashed rgba(200,200,200,1)'>" . $msg . "</td></tr>";
  //   $html .= "</tbody></table><br/><p style='text-align:right'>如果需要詳細列表，可以置<a href='" . base_url ('admin') . "' style='color:rgba(96,156,255,1);margin:0 2px'>管理後台</a>檢閱。</p></section></article>";
    
  //   $this->load->library ('OaMailGun');
  //   $mail = new OaMailGun ();
  //   $result = $mail->sendMessage (array (
  //             'from' => Cfg::setting ('mail_gun', 'user', 'system', 'name') . ' <' . Cfg::setting ('mail_gun', 'user', 'system', 'email') . '>',
  //             'to' => 'OA' . ' <comdan66@gmail.com>',
  //             'subject' => Cfg::setting ('mail_gun', 'user', 'system', 'subject'),
  //             'html' => $html
  //           ));
  // }
  // public function spec () {
  //   $this->set_frame_path ('frame', 'pure')
  //        ->load_view ();
  // }
  public function index () {
    $march19 = '2016-04-25 00:00:00';

    $temp = new DateTime ($march19);
    $day_count = $temp->diff (new DateTime (date ('Y-m-d H:i:s')))->format ("%a");
    
    $path = Path::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', 1, Path::IS_ENABLED)));
    $polyline = json_encode (array_map (function ($p) { return array ('a' => $p->latitude, 'n' => $p->longitude);}, $path->points));

    $prev = array ();
    $next = array (
        'url' => base_url ('articles'),
        'title' => '笨港文化'
      );

    $store = Store::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', 1, Store::IS_ENABLED)));
    $store = json_encode (array (
        'u' => base_url ('stores', $store->id),
        't' => $store->title,
        'c' => $store->mini_content (50),
        'i' => $store->icon_url (),
        'o' => $store->cover->url ('230x115c'),
        'a' => $store->latitude,
        'n' => $store->longitude
      ));

    $this->set_title ('北港朝天宮')
         ->set_subtitle ('北港朝天宮')
         ->add_css (resource_url ('resource', 'css', 'OA-mobileScrollView', 'OA-mobileScrollView.css'))
         ->add_js (Cfg::setting ('google', 'client_js_url'), false)
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_js (resource_url ('resource', 'javascript', 'jquery-ui_v1.11.4', 'jquery-ui.min.js'))
         ->add_js (resource_url ('resource', 'javascript', 'OA-mobileScrollView', 'OA-mobileScrollView.min.js'))
         ->load_view (array (
            'day_count' => $day_count,
            'path' => $path,
            'store' => $store,
            'polyline' => $polyline,
            'prev' => $prev,
            'next' => $next,
          ));
  }
}
