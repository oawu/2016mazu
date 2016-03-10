<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_controller extends Oa_controller {

  public function __construct () {
    parent::__construct ();

    $class  = $this->get_class ();
    $method = $this->get_method ();

    if ($this->input->is_cli_request ())
      return;
    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
var_dump (Cfg::setting ('site', 'menu'));
exit ();
    $menus_list = array_map (function ($menus) use ($class, $method, &$has_active) {
      return array_map (function ($item) use ($class, $method, &$has_active) {
        $has_active |= ($a = ((isset ($item['class']) && $item['class']) && ($class == $item['class']) && (isset ($item['method']) && $item['method']) && ($method == $item['method'])) || (((isset ($item['class']) && $item['class'])) && ($class == $item['class']) && !((isset ($item['method']) && $item['method']))) || (!(isset ($item['class']) && $item['class']) && (isset ($item['method']) && $item['method']) && ($method == $item['method'])));
        return array_merge ($item, array ('active' => $a && !isset ($item['uri'])));
      }, $menus);
    }, array_filter (array_map (function ($group) {
      return array_filter ($group, function ($item) {
        return in_array ('all', $item['roles']) || (User::current () && User::current ()->in_roles ($item['roles']));
      });
    }, Cfg::setting ('site', 'menu'))));

    if (!$has_active && (($class != 'main') || ($method != 'index')))
      return redirect_message (array (), array (
          '_flash_message' => ''
        ));

    $this->set_componemt_path ('component', 'site')
         ->set_frame_path ('frame', 'site')
         ->set_content_path ('content', 'site')
         ->set_public_path ('public')

         ->set_title (Cfg::setting ('site', 'title'))
         ->_add_meta ()->_add_css ()->_add_js ()
         
         ->add_hidden (array ('id' => 'ajax_pv_url', 'value' => base_url ('ajax', 'pv')))
         ->add_param ('_menus_list', $menus_list)
         ;
  }

  private function _add_meta () {
    return $this->add_meta (array ('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui'))
                ->add_meta (array ('name' => 'robots', 'content' => 'index,follow'))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', Cfg::setting ('site', 'keywords'))))
                ->add_meta (array ('name' => 'description', 'content' => Cfg::setting ('site', 'description')))

                ->add_meta (array ('property' => 'og:site_name', 'content' => Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:url', 'content' => current_url ()))
                ->add_meta (array ('property' => 'og:title', 'content' => ''))
                ->add_meta (array ('property' => 'og:description', 'content' => ''))
                ->add_meta (array ('property' => 'fb:admins', 'content' => Cfg::setting ('facebook', 'admins')))
                ->add_meta (array ('property' => 'fb:app_id', 'content' => Cfg::setting ('facebook', 'appId')))
                ->add_meta (array ('property' => 'og:locale', 'content' => 'zh_TW'))
                ->add_meta (array ('property' => 'og:locale:alternate', 'content' => 'en_US'))
                ->add_meta (array ('property' => 'og:type', 'content' => 'article'))
                ->add_meta (array ('property' => 'article:author', 'content' => Cfg::setting ('facebook', 'author', 'link')))
                ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'author', 'link')))
                ->add_meta (array ('property' => 'og:image', 'content' => $img = resource_url ('resource', 'image', 'og', 'larger.jpg'), 'alt' => Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
                ->add_meta (array ('property' => 'og:image:width', 'content' => '1200'))
                ->add_meta (array ('property' => 'og:image:height', 'content' => '630'))
    ;
  }

  private function _add_css () {
    return $this->add_css ('https://fonts.googleapis.com/css?family=Open+Sans:400,700', false)

                ->append_css (base_url ('application', 'cell', 'views', 'site_frame_cell', 'header', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'site_frame_cell', 'wrapper_left', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'site_frame_cell', 'tabs', 'content.css'))
                ->append_css (base_url ('application', 'cell', 'views', 'site_frame_cell', 'footer', 'content.css'))
                ;
  }

  private function _add_js () {
    return $this->add_js (resource_url ('resource', 'javascript', 'jrit.js'))

                ->append_js (base_url ('application', 'cell', 'views', 'site_frame_cell', 'header', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'site_frame_cell', 'wrapper_left', 'content.js'))
                ->append_js (base_url ('application', 'cell', 'views', 'site_frame_cell', 'tabs', 'content.js'))
                ;
  }
}