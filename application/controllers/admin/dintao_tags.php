<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Dintao_tags extends Admin_controller {
  private $uri_1 = null;
  private $tag = null;

  public function __construct () {
    parent::__construct ();

    $this->uri_1 = 'dintao-tags';

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = DintaoTag::find_by_id ($id))))
        return redirect_message (array ('admin', $this->uri_1), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('標籤列表', array ('href' => base_url ('admin', $this->uri_1), 'index' => 1))
         ->add_tab ('新增標籤', array ('href' => base_url ('admin', $this->uri_1, 'add'), 'index' => 2))
         ->add_param ('uri_1', $this->uri_1)
         ;
  }

  public function index ($offset = 0) {
    $columns = array (
        array ('key' => 'name', 'title' => '名稱', 'sql' => 'name LIKE ?'), 
      );
    $configs = array ('admin', $this->uri_1, '%s');
    $conditions = conditions ($columns, $configs);

    $limit = 25;
    $total = DintaoTag::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $tags = DintaoTag::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('藝陣標籤列表')
                ->add_hidden (array ('id' => 'is_on_site_url', 'value' => base_url ('admin', $this->uri_1, 'is_on_site')))
                ->load_view (array (
                    'tags' => $tags,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }

  public function add () {
    $posts = Session::getData ('posts', true);
    
    return $this->set_tab_index (2)
                ->set_subtitle ('新增藝陣標籤')
                ->load_view (array (
                    'posts' => $posts
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

    $create = DintaoTag::transaction (function () use ($posts) {
      return verifyCreateOrm ($tag = DintaoTag::create (array_intersect_key ($posts, DintaoTag::table ()->columns)));
    });

    if (!$create)
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯標籤', array ('href' => base_url ('admin', $this->uri_1, 'edit', $this->tag->id), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯藝陣標籤')
                ->load_view (array (
                    'posts' => $posts,
                    'tag' => $this->tag
                  ));
  }

  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->tag->table ()->columns))
      foreach ($columns as $column => $value)
        $this->tag->$column = $value;
    
    $tag = $this->tag;
    $update = DintaoTag::transaction (function () use ($tag, $posts) {
      return $tag->save ();
    });

    if (!$update)
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '更新成功！'
      ));
  }

  public function destroy () {
    $tag = $this->tag;
    $delete = DintaoTag::transaction (function () use ($tag) {
      return $tag->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->uri_1), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  public function is_on_site ($id = 0) {
    if (!($id && ($tag = DintaoTag::find_by_id ($id, array ('select' => 'id, is_on_site, updated_at')))))
      return $this->output_json (array ('status' => false, 'message' => '當案不存在，或者您的權限不夠喔！'));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_is_on_site_posts ($posts))
      return $this->output_json (array ('status' => false, 'message' => $msg, 'content' => DintaoTag::$isOnSiteNames[$tag->is_on_site]));

    if ($columns = array_intersect_key ($posts, $tag->table ()->columns))
      foreach ($columns as $column => $value)
        $tag->$column = $value;

    $update = DintaoTag::transaction (function () use ($tag) { return $tag->save (); });

    if (!$update)
      return $this->output_json (array ('status' => false, 'message' => '更新失敗！', 'content' => DintaoTag::$isOnSiteNames[$tag->is_on_site]));

    return $this->output_json (array ('status' => true, 'message' => '更新成功！', 'content' => DintaoTag::$isOnSiteNames[$tag->is_on_site]));
  }

  private function _validation_posts (&$posts) {
    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫名稱！';

    if (!(isset ($posts['is_on_site']) && is_numeric ($posts['is_on_site'] = trim ($posts['is_on_site'])) && in_array ($posts['is_on_site'], array_keys (DintaoTag::$isOnSiteNames))))
      $posts['is_on_site'] = DintaoTag::NO_ON_SITE_NAMES;

    return '';
  }
  private function _validation_is_on_site_posts (&$posts) {
    if (!(isset ($posts['is_on_site']) && is_numeric ($posts['is_on_site']) && in_array ($posts['is_on_site'], array_keys (DintaoTag::$isOnSiteNames))))
      return '參數錯誤！';
    return '';
  }
}
