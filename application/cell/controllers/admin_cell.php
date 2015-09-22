<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Admin_cell extends Cell_Controller {

  /* render_cell ('admin_cell', 'wrapper_left', var1, ..); */
  // public function _cache_wrapper_left () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function wrapper_left () {
    $class = $this->CI->get_class ();
    $method = $this->CI->get_method ();

    $item_lists = array (
      '權限' => array (
          array ('name' => '首頁', 'href' => base_url ('admin'), 'icon' => 'icon-home', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
          array ('name' => '角色設定', 'href' => base_url ('admin', 'roles'), 'icon' => 'icon-user', 'target' => '_self', 'visible' => true, 'active' => ($class == 'roles')),
          array ('name' => '使用者設定', 'href' => base_url ('admin', 'users'), 'icon' => 'icon-user2', 'target' => '_self', 'visible' => true, 'active' => ($class == 'users')),
        ),
      '文章系統'=> array (
          array ('name' => '美食上搞', 'href' => base_url ('admin'), 'icon' => 'icon-spoon-knife', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
          array ('name' => '陣頭上搞', 'href' => base_url ('admin'), 'icon' => 'icon-file-text2', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
          array ('name' => '照片上搞', 'href' => base_url ('admin'), 'icon' => 'icon-images', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
        ),
      '郵件系統'=> array (
          array ('name' => '問題清單', 'href' => base_url ('admin'), 'icon' => 'icon-help', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
          array ('name' => '發送郵件', 'href' => base_url ('admin'), 'icon' => 'icon-mail', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
        ),
      '系統紀錄'=> array (
          array ('name' => '排程紀錄', 'href' => base_url ('admin'), 'icon' => 'icon-clipboard', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
          array ('name' => '郵件紀錄', 'href' => base_url ('admin'), 'icon' => 'icon-paperplane', 'target' => '_self', 'visible' => true, 'active' => ($class == 'maind') && ($method == 'index')),
        ),
    );
    return $this->load_view (array (
        'item_lists' => $item_lists
      ));
  }

  /* render_cell ('admin_cell', 'nav', var1, ..); */
  // public function nav () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function nav ($subtitle) {
    return $this->load_view (array (
      'subtitle' => $subtitle
      ));
  }

  /* render_cell ('admin_cell', 'loading', var1, ..); */
  // public function loading () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function loading () {
    return $this->load_view ();
  }

  /* render_cell ('admin_cell', 'footer', var1, ..); */
  // public function footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->load_view (array (
      ));
  }

  /* render_cell ('admin_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}