<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Site_controller extends Oa_controller {

  public function __construct () {
    parent::__construct ();

    $this
         ->set_componemt_path ('component', 'site')
         ->set_frame_path ('frame', 'site')
         ->set_content_path ('content', 'site')
         ->set_public_path ('public')

         ->set_title (Cfg::setting ('site', 'main', 'title'))

         ->_add_meta ()
         ->_add_css ()
         ->_add_js ()
         ;
  }

  private function _add_meta () {
    return $this;
  }

  private function _add_css () {
    return $this->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox.css'))
                ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.css'))
                ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.css'))
                ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
                ;
  }

  private function _add_js () {
    return $this->add_js (base_url ('resource', 'javascript', 'jquery_v1.11.3', 'jquery-1.11.3.min.js'))
                ->add_js (base_url ('resource', 'javascript', 'imgLiquid_v0.9.944', 'imgLiquid-min.js'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox.js'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.js'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.js'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-media.js'))
                ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
                ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'));
                ;
  }
}