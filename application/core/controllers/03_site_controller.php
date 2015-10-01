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
    return $this->add_meta (array ('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui'))
                
                ->add_meta (array ('name' => 'robots', 'content' => 'index,follow'))
                ->add_meta (array ('name' => 'author', 'content' => '吳政賢(OA Wu)'))
                
                ->add_meta (array ('name' => 'keywords', 'content' => Cfg::setting ('site', 'main', 'keywords')))
                ->add_meta (array ('name' => 'description', 'content' => Cfg::setting ('site', 'main', 'description')))

                ->add_meta (array ('property' => 'og:site_name', 'content' => Cfg::setting ('site', 'main', 'title')))
                ->add_meta (array ('property' => 'og:url', 'content' => current_url ()))
                
                ->add_meta (array ('property' => 'og:title', 'content' => ''))
                ->add_meta (array ('property' => 'og:description', 'content' => ''))
                
                ->add_meta (array ('property' => 'fb:admins', 'content' => Cfg::setting ('facebook', 'admins')))
                ->add_meta (array ('property' => 'fb:app_id', 'content' => Cfg::setting ('facebook', 'appId')))

                ->add_meta (array ('property' => 'og:locale', 'content' => 'zh_TW'))
                ->add_meta (array ('property' => 'og:locale:alternate', 'content' => 'en_US'))
                ->add_meta (array ('property' => 'og:type', 'content' => 'city'))

                ->add_meta (array ('property' => 'og:image', 'content' => '', 'alt' => Cfg::setting ('site', 'main', 'title')))
                ->add_meta (array ('property' => 'og:image:type', 'content' => 'image/jpeg'))
                ->add_meta (array ('property' => 'og:image:width', 'content' => '1200'))
                ->add_meta (array ('property' => 'og:image:height', 'content' => '630'))

    ;
  }

  private function _add_css () {
    return $this->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'nav', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'wrapper_left', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'tabs', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'footer', 'content.css'))
                ;
    return $this;
  }

  private function _add_js () {
    return $this->add_js (base_url ('resource', 'javascript', 'jquery_v1.11.3', 'jquery-1.11.3.min.js'))
                ->add_js (base_url ('resource', 'javascript', 'imgLiquid_v0.9.944', 'imgLiquid-min.js'))
                ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
                ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'frame_cell', 'nav', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'frame_cell', 'wrapper_left', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'frame_cell', 'tabs', 'content.js'))
                ;
  }
}