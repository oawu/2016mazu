<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Paths extends Admin_controller {
  private $uri_1 = null;
  private $path = null;

  public function __construct () {
    parent::__construct ();

    $this->uri_1 = 'paths';

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->path = Path::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('admin', $this->uri_1), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('路線列表', array ('href' => base_url ('admin', $this->uri_1), 'index' => 1))
         ->add_tab ('新增路線', array ('href' => base_url ('admin', $this->uri_1, 'add'), 'index' => 2))
         ->add_param ('uri_1', $this->uri_1)
         ;
  }

  public function index ($offset = 0) {
    $columns = array (
        array ('key' => 'user_id',    'title' => '作者',    'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
        array ('key' => 'title',      'title' => '標題',    'sql' => 'title LIKE ?'), 
        array ('key' => 'keywords',   'title' => '關鍵字',  'sql' => 'keywords LIKE ?'), 
        array ('key' => 'pv_bigger',  'title' => 'PV 大於', 'sql' => 'pv >= ?'), 
        array ('key' => 'pv_smaller', 'title' => 'PV 小於', 'sql' => 'pv <= ?'), 
      );

    $configs = array ('admin', $this->uri_1, '%s');
    $conditions = conditions ($columns, $configs);
    Path::addConditions ($conditions, 'destroy_user_id IS NULL');

    $limit = 25;
    $total = Path::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $paths = Path::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('路線列表')
                ->add_hidden (array ('id' => 'is_enabled_url', 'value' => base_url ('admin', $this->uri_1, 'is_enabled')))
                ->load_view (array (
                    'paths' => $paths,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }

  public function add () {
    $posts = Session::getData ('posts', true);

    return $this->set_tab_index (2)
                ->set_subtitle ('新增路線')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'posts' => $posts,
                    'tags' => PathTag::all ()
                  ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['pv'] = 0;
    $posts['length'] = 0;
    $posts['image'] = '';
    $posts['user_id'] = User::current ()->id;

    $path = null;
    $create = Path::transaction (function () use (&$path, $posts) {
      if (!verifyCreateOrm ($path = Path::create (array_intersect_key ($posts, Path::table ()->columns))))
        return false;

      return true;
    });

    if (!($create && $path))
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    if ($posts['points'])
      foreach ($posts['points'] as $point)
        PathPoint::transaction (function () use ($path, $point) {
          return verifyCreateOrm (PathPoint::create (array (
              'path_id' => $path->id,
              'latitude' => $point['lat'],
              'longitude' => $point['lng'],
            )));
        });

    delay_job ('paths', 'update_image_and_length', array ('id' => $path->id));

    if ($posts['tag_ids'] && ($tag_ids = column_array (PathTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id')))
      foreach ($tag_ids as $tag_id)
        PathTagMapping::transaction (function () use ($tag_id, $path) {
          return verifyCreateOrm (PathTagMapping::create (array_intersect_key (array (
            'path_id' => $path->id,
            'path_tag_id' => $tag_id,
            ), PathTagMapping::table ()->columns)));
        });

    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯路線', array ('href' => base_url ('admin', $this->uri_1, 'edit', $this->path->id), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯路線')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->load_view (array (
                    'posts' => $posts,
                    'tags' => PathTag::all (),
                    'path' => $this->path
                  ));
  }

  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->path->table ()->columns))
      foreach ($columns as $column => $value)
        $this->path->$column = $value;
    
    $path = $this->path;
    $update = Path::transaction (function () use ($path, $posts) {
      if (!$path->save ())
        return false;

      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->uri_1, $this->path->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    if ($path->points)
      foreach ($path->points as $point)
        PathPoint::transaction (function () use ($point) {
          return $point->destroy ();
        });

    if ($posts['points'])
      foreach ($posts['points'] as $point)
        PathPoint::transaction (function () use ($path, $point) {
          return verifyCreateOrm (PathPoint::create (array (
              'path_id' => $path->id,
              'latitude' => $point['lat'],
              'longitude' => $point['lng'],
            )));
        });

    delay_job ('paths', 'update_image_and_length', array ('id' => $path->id));

    $ori_ids = column_array ($path->mappings, 'path_tag_id');
    if (($del_ids = array_diff ($ori_ids, $posts['tag_ids'])) && ($mappings = PathTagMapping::find ('all', array ('select' => 'id, path_tag_id', 'conditions' => array ('path_id = ? AND path_tag_id IN (?)', $path->id, $del_ids)))))
      foreach ($mappings as $mapping)
        PathTagMapping::transaction (function () use ($mapping) {
          return $mapping->destroy ();
        });

    if (($add_ids = array_diff ($posts['tag_ids'], $ori_ids)) && $tag_ids = column_array (PathTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $add_ids))), 'id'))
      foreach ($tag_ids as $tag_id)
        PathTagMapping::transaction (function () use ($tag_id, $path) {
          return verifyCreateOrm (PathTagMapping::create (Array_intersect_key (array (
              'path_tag_id' => $tag_id,
              'path_id' => $path->id,
            ), PathTagMapping::table ()->columns)));
        });

    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '更新成功！'
      ));
  }

  public function destroy () {
    if (!User::current ()->id)
      return redirect_message (array ('admin', $this->uri_1), array (
          '_flash_message' => '刪除失敗！',
        ));

    $posts = array (
        'destroy_user_id' => User::current ()->id
      );

    $path = $this->path;
    if ($columns = array_intersect_key ($posts, $path->table ()->columns))
      foreach ($columns as $column => $value)
        $path->$column = $value;

    $delete = Path::transaction (function () use ($path) {
      return $path->save ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->uri_1), array (
          '_flash_message' => '刪除失敗！',
        ));

    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  public function is_enabled ($id = 0) {
    if (!($id && ($path = Path::find_by_id ($id, array ('select' => 'id, is_enabled, updated_at')))))
      return $this->output_json (array ('status' => false, 'message' => '當案不存在，或者您的權限不夠喔！'));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_is_enabled_posts ($posts))
      return $this->output_json (array ('status' => false, 'message' => $msg, 'content' => Path::$isIsEnabledNames[$path->is_enabled]));

    if ($columns = array_intersect_key ($posts, $path->table ()->columns))
      foreach ($columns as $column => $value)
        $path->$column = $value;

    $update = Path::transaction (function () use ($path) { return $path->save (); });

    if (!$update)
      return $this->output_json (array ('status' => false, 'message' => '更新失敗！', 'content' => Path::$isIsEnabledNames[$path->is_enabled]));

    return $this->output_json (array ('status' => true, 'message' => '更新成功！', 'content' => Path::$isIsEnabledNames[$path->is_enabled]));
  }

  private function _validation_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';

    if (!(isset ($posts['keywords']) && ($posts['keywords'] = trim ($posts['keywords']))))
      return '沒有填寫關鍵字！';

    if (!(isset ($posts['points']) && ($posts['points'] = array_filter (array_map (function ($point) { return isset ($point['lat']) && isset ($point['lng']) ? array ('lat' => $point['lat'], 'lng' => $point['lng']) : null;}, $posts['points'])))))
      return '沒有點擊地圖規劃路線！';

    if (!(isset ($posts['tag_ids']) && ($posts['tag_ids'] = array_filter (array_map ('trim', $posts['tag_ids']))) && ($posts['tag_ids'] = column_array (PathTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id'))))
      $posts['tag_ids'] = array ();

    if (!(isset ($posts['is_enabled']) && is_numeric ($posts['is_enabled'] = trim ($posts['is_enabled'])) && in_array ($posts['is_enabled'], array_keys (Path::$isIsEnabledNames))))
      $posts['is_enabled'] = Path::NO_ENABLED;

    return '';
  }
  private function _validation_is_enabled_posts (&$posts) {
    if (!(isset ($posts['is_enabled']) && is_numeric ($posts['is_enabled']) && in_array ($posts['is_enabled'], array_keys (Path::$isIsEnabledNames))))
      return '參數錯誤！';
    return '';
  }
}
