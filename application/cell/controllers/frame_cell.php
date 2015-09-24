<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Frame_cell extends Cell_Controller {

  /* render_cell ('frame_cell', 'wrapper_left', var1, ..); */
  // public function _cache_wrapper_left ($type, $class = null, $method = null) {
  //   $class = $class ? $class : $this->CI->get_class ();
  //   $method = $method ? $method : $this->CI->get_method ();

  //   return array ('time' => 60 * 60, 'key' => $type . '_' . $class . '_' . $method);
  // }
  public function wrapper_left ($type, $class = null, $method = null) {
    $class = $class ? $class : $this->CI->get_class ();
    $method = $method ? $method : $this->CI->get_method ();
    $menus_list = Cfg::setting ('menu', $type);

    return $this->load_view (array (
        'menus_list' => $menus_list,
        'class' => $class,
        'method' => $method
      ));
  }

  /* render_cell ('frame_cell', 'nav', var1, ..); */
  // public function _cache_nav ($type, $subtitle) {
  //   return array ('time' => 60 * 60, 'key' => $type . '_' . $subtitle);
  // }
  public function nav ($type, $subtitle = '') {
    $menus = array ();
    if ($type == 'admin') {
      array_push ($menus, array ('text' => '前台', 'class' => 'icon-home', 'href' => base_url ()));
      array_push ($menus, array ('text' => '登出', 'class' => 'icon-exit top_line logout', 'href' => Fb::logoutUrl ('platform', 'sign_out')));
    }

    if ($type == 'site') {
      array_push ($menus, array ('text' => '分享', 'class' => 'icon-share share', 'href' => ''));
      
      if (!User::current ())
        array_push ($menus, array ('text' => '登入', 'class' => 'icon-enter top_line login', 'href' => Fb::loginUrl ('platform', 'fb_sign_in')));
      else {
        if (array_intersect (Cfg::setting ('admin', 'roles'), User::current ()->roles ()))
          array_push ($menus, array ('text' => '管理', 'class' => 'icon-user top_line admin', 'href' => base_url ('admin')));
        
        array_push ($menus, array ('text' => '登出', 'class' => 'icon-exit logout', 'href' => Fb::logoutUrl ('platform', 'sign_out')));
      }
    }

    return $this->load_view (array (
        'menus' => $menus,
        'subtitle' => $subtitle
      ));
  }

  /* render_cell ('frame_cell', 'footer', var1, ..); */
  // public function footer ($type) {
  //   return array ('time' => 60 * 60, 'key' => $type);
  // }
  public function footer ($type) {
    return $this->load_view ();
  }

  /* render_cell ('frame_cell', 'tabs', var1, ..); */
  // public function tabs ($type, $tabs = array (), $class = null, $method = null) {
  //   return array ('time' => 60 * 60, 'key' => $type . '_' . implode ('|', array_keys ($tabs)) . '_' . $class . '_' . $method);
  // }
  public function tabs ($type, $tabs = array (), $class = null, $method = null) {
    $class = $class ? $class : $this->CI->get_class ();
    $method = $method ? $method : $this->CI->get_method ();

    return $this->load_view (array (
        'class' => $class,
        'method' => $method,
        'tabs' => $tabs
      ));
  }

  /* render_cell ('frame_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}