<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Path_path_infos extends Admin_controller {
  private $uri_1 = null;
  private $uri_2 = null;
  private $path  = null;
  private $info  = null;

  public function __construct () {
    parent::__construct ();

    $this->uri_1     = 'path';
    $this->uri_2     = 'infos';

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->path = Path::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
      return redirect_message (array ('admin', 'paths'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->info = PathInfo::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_param ('class', 'paths')
         ->add_tab ('路線列表', array ('href' => base_url (array ('admin', 'paths')), 'index' => 1))
         ->add_tab ($this->path->title . '內的資訊列表', array ('href' => base_url ('admin', $this->uri_1, $this->path->id, $this->uri_2), 'index' => 2))
         ->add_tab ('新增' . $this->path->title . '的資訊', array ('href' => base_url ('admin', $this->uri_1, $this->path->id, $this->uri_2, 'add'), 'index' => 3))
         ->add_param ('uri_1', $this->uri_1)
         ->add_param ('uri_2', $this->uri_2)
         ->add_param ('path', $this->path)
         ;
  }

  public function index ($path_id, $offset = 0) {
    $columns = array (
        array ('key' => 'user_id',    'title' => '作者',    'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
        array ('key' => 'title',      'title' => '標題',    'sql' => 'title LIKE ?'), 
        array ('key' => 'content',    'title' => '內容',    'sql' => 'content LIKE ?'), 
      );

    $configs = array ('admin', $this->uri_2, $this->path->id,  $this->uri_2, '%s');
    $conditions = conditions ($columns, $configs);
    PathInfo::addConditions ($conditions, 'path_id = ? AND destroy_user_id IS NULL', $this->path->id);

    $limit = 25;
    $total = PathInfo::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $infos = PathInfo::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (2)
                ->set_subtitle ($this->path->title . '內的資訊列表')
                ->load_view (array (
                    'infos' => $infos,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }

  public function add ($path_id) {
    $posts = Session::getData ('posts', true);

    return $this->set_tab_index (3)
                ->set_subtitle ('新增' . $this->path->title . '的資訊')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'posts' => $posts,
                  ));
  }

  public function create ($path_id) {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!($cover || $posts['url']))
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, 'add'), array (
          '_flash_message' => '請選擇陣頭(gif、jpg、png)檔案，或提供陣頭網址!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['path_id'] = $this->path->id;
    $posts['cover'] = '';
    $posts['user_id'] = User::current ()->id;

    $info = null;
    $create = PathInfo::transaction (function () use (&$info, $posts, $cover) {
      if (!verifyCreateOrm ($info = PathInfo::create (array_intersect_key ($posts, PathInfo::table ()->columns))))
        return false;

      if (!(($cover && $info->cover->put ($cover)) || ($posts['url'] && $info->cover->put_url ($posts['url']))))
        return false;

      return true;
    });


    if (!($create && $info))
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    delay_job ('path_infos', 'update_cover_color_and_dimension', array ('id' => $info->id));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯資訊', array ('href' => base_url ('admin', $this->uri_1, $this->path->id, $this->uri_2, $this->info->id, 'edit'), 'index' => 4))
                ->set_tab_index (4)
                ->set_subtitle ('編輯資訊')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'posts' => $posts,
                    'info' => $this->info
                  ));
  }

  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, $this->info->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!((string)$this->info->cover || $cover || $posts['url']))
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, $this->info->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, $this->info->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->info->table ()->columns))
      foreach ($columns as $column => $value)
        $this->info->$column = $value;
    
    $info = $this->info;
    $update = PathInfo::transaction (function () use ($info, $posts, $cover) {
      if (!$info->save ())
        return false;

      if ($cover && !$info->cover->put ($cover))
        return false;

      if ($posts['url'] && !$info->cover->put_url ($posts['url']))
        return false;
      
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2, $this->info->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    if ($cover || $posts['url'])
      delay_job ('path_infos', 'update_cover_color_and_dimension', array ('id' => $info->id));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2), array (
        '_flash_message' => '更新成功！'
      ));
  }

  public function destroy () {
    if (!User::current ()->id)
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2), array (
          '_flash_message' => '刪除失敗！',
        ));

    $posts = array (
        'destroy_user_id' => User::current ()->id
      );

    $info = $this->info;
    if ($columns = array_intersect_key ($posts, $info->table ()->columns))
      foreach ($columns as $column => $value)
        $info->$column = $value;

    $delete = Dintao::transaction (function () use ($info) {
      return $info->save ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2), array (
          '_flash_message' => '刪除失敗！',
        ));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1, $this->path->id, $this->uri_2), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  private function _validation_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';

    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';
    
    if (!(isset ($posts['type']) && ($posts['type'] = trim ($posts['type'])) && in_array ($posts['type'], array_keys (PathInfo::$type_names))))
      return '沒有選則類型！';

    if (!(isset ($posts['latitude']) && ($posts['latitude'] = trim ($posts['latitude']))))
      return '沒有緯度，請點選地圖決定地點！';

    if (!(isset ($posts['longitude']) && ($posts['longitude'] = trim ($posts['longitude']))))
      return '沒有經度，請點選地圖決定地點！';

    return '';
  }
  private function _clean () {
    $this->output->delete_all_cache ();
  }
}
