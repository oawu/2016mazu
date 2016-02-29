<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Admin_controller extends Oa_controller {

  public function __construct () {
    parent::__construct ();

    if (!(User::current () && User::current ()->is_login ())) {
      Session::setData ('_flash_message', '', true);
      return redirect_message (array ('login'), array (
          '_flash_message' => '請先登入，或者您沒有後台權限！'
        ));
      // return show_404();
    }

    $class  = $this->get_class ();
    $method = $this->get_method ();

    $menus_list = array_map (function ($menus) use ($class, $method, &$has_active) {
      return array_map (function ($item) use ($class, $method, &$has_active) {
        $has_active |= ($a = ((isset ($item['class']) && $item['class']) && ($class == $item['class']) && (isset ($item['method']) && $item['method']) && ($method == $item['method'])) || (((isset ($item['class']) && $item['class'])) && ($class == $item['class']) && !((isset ($item['method']) && $item['method']))) || (!(isset ($item['class']) && $item['class']) && (isset ($item['method']) && $item['method']) && ($method == $item['method'])));
        return array_merge ($item, array ('active' => $a && !isset ($item['uri'])));
      }, $menus);
    }, array_filter (array_map (function ($group) {
      return array_filter ($group, function ($item) {
        return in_array ('all', $item['roles']) || (User::current () && User::current ()->in_roles ($item['roles']));
      });
    }, Cfg::setting ('admin', 'menu'))));


    $this
         ->set_componemt_path ('component', 'admin')
         ->set_frame_path ('frame', 'admin')
         ->set_content_path ('content', 'admin')
         ->set_public_path ('public')

         ->set_title (Cfg::setting ('admin', 'title'))
         ->_add_meta ()->_add_css ()->_add_js ()

         ->add_hidden (array ('id' => 'tools_ckeditors_upload_image_url', 'value' => base_url ('admin', 'tools', 'ckeditors_upload_image')))
         ->add_hidden (array ('id' => 'tools_scws_url', 'value' => base_url ('admin', 'tools', 'scws')))
         ->add_param ('_menus_list', $menus_list)
         ;
  }
  private function _add_meta () {
    return $this->add_meta (array ('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui'));
  }

  private function _add_css () {
    return $this->add_css ('http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700', false)

                ->append_css (base_url ('application', 'cell', 'views', 'admin_frame_cell', 'header', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'admin_frame_cell', 'wrapper_left', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'admin_frame_cell', 'tabs', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'admin_frame_cell', 'footer', 'content.css'))
                ;
  }

  private function _add_js () {
    return $this->add_js (resource_url ('resource', 'javascript', 'jrit.js'))

                ->add_js (base_url ('resource', 'javascript', 'autosize_v3.0.8', 'autosize.min.js'))

                ->append_js (base_url ('application', 'cell', 'views', 'admin_frame_cell', 'header', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'admin_frame_cell', 'wrapper_left', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'admin_frame_cell', 'tabs', 'content.js'))

                ->add_js (base_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'ckeditor.js'), false)
                ->add_js (base_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'config.js'), false)
                ->add_js (base_url ('resource', 'javascript', 'ckeditor_d2015_05_18', 'adapters', 'jquery.js'), false)
                ;
  }
}