<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Users extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->user = User::find_by_id ($id))))
        return redirect_message (array ('admin', $this->get_class ()), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('使用者列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1));
  }

  public function index ($offset = 0) {
    $columns = array (array ('key' => 'id',    'title' => 'ID',  'sql' => 'id = ?'), 
                      array ('key' => 'name',  'title' => '名稱', 'sql' => 'name LIKE ?'), 
                      array ('key' => 'email', 'title' => '信箱', 'sql' => 'email LIKE ?'), 
                      );
    $configs = array ('admin', $this->get_class (), '%s');
    $conditions = conditions ($columns, $configs);

    $limit = 25;
    $total = User::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $users = User::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id ASC',
        'include' => array ('roles'),
        'conditions' => $conditions
      ));

    $this->set_subtitle ('使用者列表')
         ->set_tab_index (1)
         ->load_view (array (
        'users' => $users,
        'pagination' => $pagination,
        'columns' => $columns
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯 ' . $this->user->name . '', array ('href' => base_url ('admin', $this->get_class (), $this->user->id, 'edit'), 'index' => 2))
                ->set_tab_index (2)
                ->set_subtitle ('編輯 ' . $this->user->name . '')
                ->load_view (array (
                    'posts' => $posts,
                    'user' => $this->user,
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $this->user->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $this->user->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->user->table ()->columns))
      foreach ($columns as $column => $value)
        $this->user->$column = $value;

    $user = $this->user;
    $update = user::transaction (function () use ($user) {
      return $user->save ();
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $this->user->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));


    $ori_keys = column_array ($user->roles, 'name');

    if (($del_keys = array_diff ($ori_keys, $posts['roles'])) && ($roles = UserRole::find ('all', array ('select' => 'id', 'conditions' => array ('user_id = ? AND name IN (?)', $user->id, $del_keys)))))
      foreach ($roles as $role)
        UserRole::transaction (function () use ($role) {
          return $role->destroy ();
        });

    if ($add_keys = array_diff ($posts['roles'], $ori_keys))
      foreach ($add_keys as $add_key)
        UserRole::transaction (function () use ($add_key, $user) {
          return verifyCreateOrm (UserRole::create (Array_intersect_key (array ('name' => $add_key, 'user_id' => $user->id), UserRole::table ()->columns)));
        });

    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫名稱！';

    if (!(isset ($posts['email']) && ($posts['email'] = trim ($posts['email']))))
      return '沒有填寫電子郵件！';


    if (!(isset ($posts['facebook_url']) && ($posts['facebook_url'] = trim ($posts['facebook_url']))))
      $posts['facebook_url'] = '';

    if (!isset ($posts['roles']))
      $posts['roles'] = array ();

    $posts['roles'] = array_filter ($posts['roles'], function ($role) {
        return in_array ($role, Cfg::setting ('role', 'roles'));
      });

    return '';
  }
}
