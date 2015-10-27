<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Youtube_tags extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    $this->add_tab ('標籤列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增標籤', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2));
  }

  public function index ($offset = 0) {
    $columns = array ('name' => 'string', 'keywords' => 'string');
    $configs = array ('admin', $this->get_class (), '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'YoutubeTag', OAInput::get ())));

    $limit = 25;
    $total = YoutubeTag::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $tags = YoutubeTag::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'sort DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('影片標籤列表')
                ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', $this->get_class (), 'sort')))
                ->load_view (array (
                    'tags' => $tags,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);
    
    return $this->set_tab_index (2)
                ->set_subtitle ('新增影片標籤')
                ->load_view (array (
                    'posts' => $posts
                  ));
  }
  public function create ($index = 1) {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    // if (!$cover)
    //   return redirect_message (array ('admin', $this->get_class (), 'add'), array (
    //       '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
    //       'posts' => $posts
    //     ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['cover'] = '';
    $posts['sort'] = YoutubeTag::count ();

    $create = YoutubeTag::transaction (function () use ($posts, $cover) {
      if (!(verifyCreateOrm ($tag = YoutubeTag::create (array_intersect_key ($posts, YoutubeTag::table ()->columns)))))
        return false;

      if ($cover)
        if (!$tag->cover->put ($cover))
          return false;
      
      if ($cover)
        delay_job ('youtube_tags', 'update_cover_color', array ('id' => $tag->id));

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
  public function edit ($id = 0) {
    if (!($id && ($tag = YoutubeTag::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯標籤', array ('href' => base_url ('admin', $this->get_class (), 'edit', $tag->id), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯影片標籤')
                ->load_view (array (
                    'posts' => $posts,
                    'tag' => $tag
                  ));
  }
  public function update ($id = 0) {
    if (!($id && ($tag = YoutubeTag::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $tag->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $cover = OAInput::file ('cover');

    // if (!($cover || (string)$tag->cover))
    //   return redirect_message (array ('admin', $this->get_class (), $tag->id, 'edit'), array (
    //       '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
    //       'posts' => $posts
    //     ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $tag->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $tag->table ()->columns))
      foreach ($columns as $column => $value)
        $tag->$column = $value;

    $update = YoutubeTag::transaction (function () use ($tag, $posts, $cover) {
      if (!$tag->save ())
        return false;

      if ($cover && !$tag->cover->put ($cover))
        return false;

      if ($cover)
        delay_job ('youtube_tags', 'update_cover_color', array ('id' => $tag->id));
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $tag->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy ($id = 0) {
    if (!($id && ($tag = YoutubeTag::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $delete = YoutubeTag::transaction (function () use ($tag) {
      return $tag->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  public function sort () {
    if (!$this->input->is_ajax_request ())
      return show_404 ();
    
    if (!(($id = trim (OAInput::post ('id'))) && ($sort = trim (OAInput::post ('sort'))) && in_array ($sort, array ('up', 'down')) && ($tag = YoutubeTag::find_by_id ($id))))
      return $this->output_json (array ('status' => false));

    $total = YoutubeTag::count ();

    switch ($sort) {
      case 'up':
        $sort = $tag->sort;
        $tag->sort = $tag->sort + 1 >= $total ? 0 : $tag->sort + 1;
        break;

      case 'down':
        $sort = $tag->sort;
        $tag->sort = $tag->sort - 1 < 0 ? $total - 1 : $tag->sort - 1;
        break;
    }

    YoutubeTag::addConditions ($conditions, 'sort = ?', $tag->sort);

    $update = YoutubeTag::transaction (function () use ($conditions, $tag, $sort) {
      if (($next = YoutubeTag::find ('one', array ('conditions' => $conditions))) && (($next->sort = $sort) || true))
        if (!$next->save ()) return false;
      if (!$tag->save ()) return false;

      return true;
    });
    return $this->output_json (array ('status' => $update));
  }
  private function _validation_posts (&$posts) {
    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫名稱！';
    if (!(isset ($posts['keywords']) && ($posts['keywords'] = trim ($posts['keywords']))))
      return '沒有填寫關鍵字！';

    return '';
  }
}
