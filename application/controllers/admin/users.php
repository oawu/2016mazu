<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Users extends Admin_controller {

  private function _validation_posts (&$posts) {
    $roles = Cfg::setting ('role');

    if (isset ($posts['roles']) && $posts['roles'])
      if (!array_intersect ($posts['roles'], array_keys ($roles)))
        return '錯誤的角色！';
    return '';
  }

  public function set_roles ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect_message (array ('admin', 'users'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'users', $menu->id, 'roles'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'users', $menu->id, 'roles'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $roles = isset($posts['roles']) ? $posts['roles'] : array ();
    unset ($posts['roles']);

    $old_roles = $user->roles ();

    if ($add_roles = array_diff ($roles, $old_roles))
      foreach ($add_roles as $role)
        UserRole::create (array ('user_id' => $user->id, 'role' => $role));

    if ($del_roles = array_diff ($old_roles, $roles))
      foreach (UserRole::find ('all', array ('select' => 'id', 'conditions' => array ('user_id = ? AND role IN (?)', $user->id, $del_roles))) as $user_role)
        $user_role->destroy ();

    return redirect_message (array ('admin', 'users'), array (
        '_flash_message' => '修改成功！'
      ));
  }
  public function roles ($id = 0) {
    if (!($user = User::find_by_id ($id)))
      return redirect_message (array ('admin', 'users'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    $posts = Session::getData ('posts', true);
    $roles = Cfg::setting ('role');

    $this->add_subtitle ('修改 ' . $user->name . ' 角色')
         ->load_view (array (
        'user' => $user,
        'posts' => $posts,
        'roles' => $roles,
      ));
  }
  public function index ($offset = 0) {

    $columns = array ('id' => 'int', 'name' => 'string', 'email' => 'string');
    $configs = array ('admin', 'users', '%s');
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'User', OAInput::get ())));

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

    $this->add_subtitle ('使用者列表')
         ->load_view (array (
        'users' => $users,
        'pagination' => $pagination,
        'has_search' => array_filter ($columns),
        'columns' => $columns
      ));
  }
}
