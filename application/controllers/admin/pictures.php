<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pictures extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    $this->add_tab ('照片列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增照片', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2));
  }

  public function index ($offset = 0) {
    $columns = array ('title' => 'string', 'keywords' => 'string');
    $configs = array ('admin', $this->get_class (), '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Picture', OAInput::get ())));

    $limit = 25;
    $total = Picture::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $pictures = Picture::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->add_subtitle ('照片列表')
                ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', $this->get_class (), 'sort')))
                ->load_view (array (
                    'pictures' => $pictures,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);
    
    return $this->set_tab_index (2)
                ->add_subtitle ('新增照片')
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
    $posts['description'] = OAInput::post ('description', false);
    $name = OAInput::file ('name');

    if (!$name)
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['name'] = '';

    $create = Picture::transaction (function () use ($posts, $name) {
      if (!(verifyCreateOrm ($picture = Picture::create (array_intersect_key ($posts, Picture::table ()->columns))) && $picture->name->put ($name)))
        return false;

      // if (!verifyCreateOrm ($mapping = PictureTagMapping::create (array (
      //     'picture_id' => $picture->id,
      //     'picture_tag_id' => $tag->id,
      //     'sort' => PictureTagMapping::count (array ('conditions' => array ('picture_tag_id = ?', $tag->id)))
      //   ))))
      //   return false;

      delay_job ('pictures', 'update_color', array ('id' => $picture->id));
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
  public function edit ($id) {
    if (!($id && ($picture = Picture::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯 ' . $picture->title . ' 照片', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 3))
                ->set_tab_index (3)
                ->add_subtitle ('編輯 ' . $picture->title . ' 照片')
                ->load_view (array (
                    'posts' => $posts,
                    'picture' => $picture,
                  ));
  }
  public function update ($id) {
    if (!($id && ($picture = Picture::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $picture->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);
    $name = OAInput::file ('name');

    if (!($name || (string)$picture->name))
      return redirect_message (array ('admin', $this->get_class (), $picture->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $picture->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $picture->table ()->columns))
      foreach ($columns as $column => $value)
        $picture->$column = $value;

    $update = Picture::transaction (function () use ($picture, $posts, $name) {
      if (!$picture->save ())
        return false;

      if ($name && !$picture->name->put ($name))
        return false;

      if ($name)
        delay_job ('pictures', 'update_color', array ('id' => $picture->id));
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $picture->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy ($id) {
    if (!($id && ($picture = Picture::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $delete = Picture::transaction (function () use ($picture) {
      return $picture->destroy ();
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
    if (!(isset ($posts['keywords']) && ($posts['keywords'] = trim ($posts['keywords']))))
      return '沒有填寫關鍵字！';
    if (!(isset ($posts['description']) && ($posts['description'] = trim ($posts['description']))))
      $posts['description'] = '';

    return '';
  }
}
