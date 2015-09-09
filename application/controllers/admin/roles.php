<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Roles extends Admin_controller {

  public function index ($offset = 0) {
    $columns = array ('id' => 'int', 'name' => 'string');
    $configs = array ('admin', 'roles', '%s');
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Role', OAInput::get ())));

    $limit = 25;
    $total = Role::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
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

  public function add () {
    $posts = Session::getData ('posts', true);

    $this->load_view (array (
        'posts' => $posts
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'roles', 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'roles', 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if(Role::find_by_name ($posts['name'], array ('select' => 'id')))
      return redirect_message (array ('admin', 'roles', 'add'), array (
          '_flash_message' => '重複的角色名稱！',
          'posts' => $posts
        ));

    if (!verifyCreateOrm ($role = Role::create ($posts)))
      return redirect_message (array ('admin', 'roles', 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    return redirect_message (array ('admin', 'roles'), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit ($id) {
    if (!($role = Role::find_by_id ($id)))
      return redirect_message (array ('admin', 'roles'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    $posts = Session::getData ('posts', true);

    $this->load_view (array (
        'posts' => $posts,
        'role' => $role
      ));
  }

  public function update ($id) {
    if (!($role = Role::find_by_id ($id)))
      return redirect_message (array ('admin', 'roles'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'roles', 'edit', $role->id), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'roles', 'edit', $role->id), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if(Role::find ('one', array ('select' => 'id', 'conditions' => array ('name = ? AND id != ?', $posts['name'], $role->id))))
      return redirect_message (array ('admin', 'roles', 'edit', $role->id), array (
          '_flash_message' => '重複的角色名稱！',
          'posts' => $posts
        ));

    foreach (array_keys (Role::table ()->columns) as $column)
      if (isset ($posts[$column]))
        $role->$column = $posts[$column];

    if (!$role->save ())
      return redirect_message (array ('admin', 'roles', 'edit', $role->id), array (
          '_flash_message' => '修改失敗！',
          'posts' => $posts
        ));

    return redirect_message (array ('admin', 'roles'), array (
        '_flash_message' => '修改成功！'
      ));
  }

  public function destroy ($id) {
    if (!($role = Role::find_by_id ($id)))
      return redirect_message (array ('admin', 'roles'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    if (!$role->destroy ())
      return redirect_message (array ('admin', 'roles'), array (
          '_flash_message' => '刪除失敗。'
        ));

    return redirect_message (array ('admin', 'roles'), array (
          '_flash_message' => '刪除成功。'
        ));
  }

  private function _validation_posts (&$posts) {
    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫角色名稱！';

    return '';
  }
}
