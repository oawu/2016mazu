<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Paths extends Admin_controller {
  private $path = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->path = Path::find_by_id ($id))))
        return redirect_message (array ('admin', 'paths'), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('路線列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增路線', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2));
  }

  public function index ($offset = 0) {
    $columns = array ('title' => 'string');
    $configs = array ('admin', $this->get_class (), '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Path', OAInput::get ())));

    $limit = 25;
    $total = Path::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $paths = Path::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('路線列表')
                ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', $this->get_class (), 'sort')))
                ->load_view (array (
                    'paths' => $paths,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);
    
    return $this->set_subtitle ('新增路線')
                ->set_tab_index (2)
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'posts' => $posts
                  ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['image'] = '';
    $posts['length'] = 0;
    $posts['user_id'] = User::current ()->id;

    $create = Path::transaction (function () use ($posts) {
      if (!(verifyCreateOrm ($path = Path::create (array_intersect_key ($posts, Path::table ()->columns)))))
        return false;

      if ($posts['points'])
        foreach ($posts['points'] as $point)
          if (!verifyCreateOrm (PathPoint::create (array (
              'path_id' => $path->id,
              'latitude' => $point['lat'],
              'longitude' => $point['lng'],
            ))))
            return false;

      delay_job ('paths', 'update_image_length', array ('id' => $path->id));

      return true;
    });

    if (!$create)
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯路線', array ('href' => base_url ('admin', $this->get_class (), 'edit', $this->path->id), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯路線')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'posts' => $posts,
                    'path' => $this->path
                  ));
  }
  public function update ($id = 0) {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $this->path->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $this->path->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['image'] = '';
    $posts['length'] = 0;

    if ($columns = array_intersect_key ($posts, $this->path->table ()->columns))
      foreach ($columns as $column => $value)
        $this->path->$column = $value;

    $path = $this->path;
    $update = Path::transaction (function () use ($path, $posts) {
      foreach ($path->points as $point)
        if (!$point->destroy ())
          return false;

      if (!$path->save ())
        return false;

      if ($posts['points'])
        foreach ($posts['points'] as $point)
          if (!verifyCreateOrm (PathPoint::create (array (
              'path_id' => $path->id,
              'latitude' => $point['lat'],
              'longitude' => $point['lng'],
            ))))
            return false;

      delay_job ('paths', 'update_image_length', array ('id' => $path->id));

      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $this->path->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy ($id) {
    $path = $this->path;
    $delete = Path::transaction (function () use ($path) {
      return $path->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';
    if (!(isset ($posts['points']) && ($posts['points'] = array_filter (array_map (function ($point) { return isset ($point['lat']) && isset ($point['lng']) ? array ('lat' => $point['lat'], 'lng' => $point['lng']) : null;}, $posts['points'])))))
      return '沒有點擊地圖規劃路線！';

    return '';
  }
}
