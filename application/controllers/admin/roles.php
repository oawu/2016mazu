<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Roles extends Admin_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index ($offset = 0) {
    $columns = array ('id' => 'int', 'name' => 'string');
    $configs = array ('admin', 'roles', '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Role', $this->input_gets ())));

    $limit = 25;
    $total = Role::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs = array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs);
    $this->pagination->initialize ($configs);
    $pagination = $this->pagination->create_links ();
    $roles = Role::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'include' => array ('user_roles', 'menu_roles'),
        'order' => 'id ASC',
        'conditions' => $conditions
      ));

    $this->load_view (array (
        'roles' => $roles,
        'pagination' => $pagination,
        'has_search' => array_filter ($columns),
        'columns' => $columns
      ));
  }
}
