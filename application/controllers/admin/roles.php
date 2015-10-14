<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Roles extends Admin_controller {

  public function index () {
    $roles = array_combine (array_keys (Cfg::setting ('role')), array_map (function ($role, $key) {
          return array_merge ($role, array (
                        'users_count' => UserRole::count (array ('conditions' => array ('role = ?', $key)))
                      ));
        }, Cfg::setting ('role'), array_keys (Cfg::setting ('role'))));

    $this->set_subtitle ('角色列表')
         ->load_view (array (
        'roles' => $roles
      ));
  }


  public function menus ($role, $offset = 0) {
    $roles = Cfg::setting ('role');
    if (!isset ($roles[$role]))
      return redirect_message (array ('admin', 'roles'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    $columns = array ('id' => 'int', 'text' => 'string', 'href' => 'string', 'class' => 'string', 'method' => 'string');
    $configs = array ('admin', 'roles', $role, 'menus', '%s');
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Menu', OAInput::get ())));

    if ($menu_ids = column_array (MenuRole::find ('all', array ('select' => 'menu_id', 'conditions' => array ('role = ?', $role))), 'menu_id'))
      Menu::addConditions ($conditions, 'id IN (?)', $menu_ids);

    $limit = 25;
    $total = Menu::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $menus = Menu::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id ASC',
        'conditions' => $conditions
      ));

    $this->set_subtitle ('屬於 ' . $roles[$role]['name'] . ' 的選項列表')
         ->load_view (array (
        'role' => $role,
        'menus' => $menus,
        'pagination' => $pagination,
        'has_search' => array_filter ($columns),
        'columns' => $columns
      ));
  }

  public function users ($role, $offset = 0) {
    $roles = Cfg::setting ('role');
    if (!isset ($roles[$role]))
      return redirect_message (array ('admin', 'roles'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    $columns = array ('id' => 'int', 'name' => 'string', 'email' => 'string');
    $configs = array ('admin', 'roles', $role, 'users', '%s');
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'User', OAInput::get ())));

    if ($user_ids = column_array (UserRole::find ('all', array ('select' => 'user_id', 'conditions' => array ('role = ?', $role))), 'user_id'))
      User::addConditions ($conditions, 'id IN (?)', $user_ids);

    $limit = 25;
    $total = User::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $users = User::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id ASC',
        'conditions' => $conditions
      ));

    $this->set_subtitle ('屬於 ' . $roles[$role]['name'] . ' 的使用者列表')
         ->load_view (array (
        'role' => $role,
        'users' => $users,
        'pagination' => $pagination,
        'has_search' => array_filter ($columns),
        'columns' => $columns
      ));
  }
}
