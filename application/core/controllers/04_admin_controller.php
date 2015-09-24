<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Admin_controller extends Oa_controller {

  public function __construct () {
    parent::__construct ();

    if (!(User::current () && array_intersect (Cfg::setting ('admin', 'roles'), User::current ()->roles ()))) {
      // Session::setData ('_flash_message', '請先登入，或者您沒有後台權限！', true);
      return show_404();
    }

    $this
         ->set_componemt_path ('component', 'admin')
         ->set_frame_path ('frame', 'admin')
         ->set_content_path ('content', 'admin')
         ->set_public_path ('public')

         ->set_title (Cfg::setting ('admin', 'main', 'title'))

         ->_add_meta ()
         ->_add_css ()
         ->_add_js ()
         ;
  }
  private function _add_meta () {
    return $this->add_meta (array ('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui'));
  }

  private function _add_css () {
    return $this->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'nav', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'wrapper_left', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'tabs', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'frame_cell', 'footer', 'content.css'))
                ;
  }

  private function _add_js () {
    return $this->add_js (base_url ('resource', 'javascript', 'jquery_v1.11.3', 'jquery-1.11.3.min.js'))
                ->add_js (base_url ('resource', 'javascript', 'imgLiquid_v0.9.944', 'imgLiquid-min.js'))
                ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
                ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
                ->add_js (base_url ('resource', 'javascript', 'autosize_v3.0.8', 'autosize.min.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'frame_cell', 'nav', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'frame_cell', 'wrapper_left', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'frame_cell', 'tabs', 'content.js'))
                ->add_js (base_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'ckeditor.js'), false)
                ->add_js (base_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'config.js'), false)
                ->add_js (base_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'adapters', 'jquery.js'), false)
                ;
  }
}