<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Path_infos extends Admin_controller {
  private $path = null;
  private $info = null;

  public function __construct () {
    parent::__construct ();

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->path = Path::find_by_id ($id))))
      return redirect_message (array ('admin', 'paths'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->info = PathInfo::find_by_id ($id))))
        return redirect_message (array ('admin', 'paths', $this->path->id, 'infos'), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_param ('class', 'paths')
         ->add_tab ('資訊列表', array ('href' => base_url ('admin', 'paths'), 'index' => 1))
         ->add_tab ('新增標籤', array ('href' => base_url ('admin', 'paths', 'add'), 'index' => 2))
         ->add_tab ('' . $this->path->title . ' 上的資訊', array ('href' => base_url ('admin', 'paths', $this->path->id, 'infos'), 'index' => 3))
         ->add_tab ('新增 ' . $this->path->title . ' 的資訊', array ('href' => base_url ('admin', 'paths', $this->path->id, 'infos', 'add'), 'index' => 4))
         ;
  }

  public function index ($id, $offset = 0) {
    $columns = array ('title' => 'string', 'description' => 'string');
    $configs = array ('admin', 'paths', $this->path->id, 'infos', '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'PathInfo', OAInput::get ())));
    PathInfo::addConditions ($conditions, 'path_id = ?', $this->path->id);

    $limit = 25;
    $total = PathInfo::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $infos = PathInfo::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id ASC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (3)
                ->set_subtitle ('' . $this->path->title . ' 上的資訊')
                ->load_view (array (
                    'path' => $this->path,
                    'infos' => $infos,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);

    return $this->set_tab_index (4)
                ->set_subtitle ('新增 ' . $this->path->title . ' 的資訊')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'path' => $this->path,
                    'posts' => $posts
                  ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!($cover || $posts['url']))
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', 'add'), array (
          '_flash_message' => '請選擇陣頭(gif、jpg、png)檔案，或提供陣頭網址!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['cover'] = '';
    $posts['image'] = '';
    $posts['path_id'] = $this->path->id;

    $create = PathInfo::transaction (function () use ($posts, $cover) {
      if (!(verifyCreateOrm ($info = PathInfo::create (array_intersect_key ($posts, PathInfo::table ()->columns))) && (($cover && $info->cover->put ($cover)) || ($posts['url'] && $info->cover->put_url ($posts['url'])))))
        return false;
      delay_job ('path_infos', 'update_image_cover_color', array ('id' => $info->id));
    });

    if (!$create)
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'paths', $this->path->id, 'infos'), array (
      '_flash_message' => '新增成功！'
    ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);

    return $this->add_tab ('編輯 ' . $this->info->title . '', array ('href' => base_url ('admin', 'paths', $this->path->id, 'infos', $this->info->id, 'edit'), 'index' => 5))
                ->set_tab_index (5)
                ->set_subtitle ('編輯 ' . $this->info->title)
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'path' => $this->path,
                    'info' => $this->info,
                    'posts' => $posts
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', $this->info->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    if (!((string)$this->info->cover || $cover || $posts['url']))
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', $this->info->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', $this->info->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['image'] = '';

    if ($columns = array_intersect_key ($posts, $this->info->table ()->columns))
      foreach ($columns as $column => $value)
        $this->info->$column = $value;

    $info = $this->info;
    $update = Path::transaction (function () use ($info, $posts, $cover) {
      if (!$info->save ())
        return false;

      if ($cover && !$info->cover->put ($cover))
        return false;

      if ($posts['url'] && !$info->cover->put_url ($posts['url']))
        return false;

      if ($cover || $posts['url'])
        delay_job ('path_infos', 'update_image_cover_color', array ('id' => $info->id));
      else
        delay_job ('path_infos', 'update_image', array ('id' => $info->id));
    });

    if (!$update)
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos', $info->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'paths', $this->path->id, 'infos'), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy () {
    $info = $this->info;
    $delete = Path::transaction (function () use ($info) {
      return $info->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', 'paths', $this->path->id, 'infos'), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', 'paths', $this->path->id, 'infos'), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';
    if (!(isset ($posts['description']) && ($posts['description'] = trim ($posts['description']))))
      return '沒有填寫描述！';
    if (!(isset ($posts['type']) && ($posts['type'] = trim ($posts['type'])) && in_array ($posts['type'], array_keys (PathInfo::$type_names))))
      return '沒有選則類型！';
    if (!(isset ($posts['latitude']) && ($posts['latitude'] = trim ($posts['latitude']))))
      return '沒有緯度，請點選地圖決定地點！';
    if (!(isset ($posts['longitude']) && ($posts['longitude'] = trim ($posts['longitude']))))
      return '沒有經度，請點選地圖決定地點！';

    return '';
  }
}
