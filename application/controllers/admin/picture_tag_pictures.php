<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Picture_tag_pictures extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    $this->add_param ('class', 'picture_tags')
         ->add_tab ('標籤列表', array ('href' => base_url ('admin', 'picture_tags'), 'index' => 1))
         ->add_tab ('新增標籤', array ('href' => base_url ('admin', 'picture_tags', 'add'), 'index' => 2));
  }

  public function index ($id, $offset = 0) {
    if (!($id && ($tag = PictureTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'picture_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $columns = array ('title' => 'string', 'keywords' => 'string');
    $configs = array ('admin', 'picture_tags', $tag->id, 'pictures', '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Picture', OAInput::get ())));
    
    if ($picture_ids = column_array (PictureTagMapping::find ('all', array ('select' => 'picture_id', 'order' => 'sort DESC', 'conditions' => array ('picture_tag_id = ?', $tag->id))), 'picture_id'))
      Picture::addConditions ($conditions, 'id IN (?)', $picture_ids);
    else
      Picture::addConditions ($conditions, 'id = ?', -1);

    $limit = 25;
    $total = Picture::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $pictures = Picture::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => $picture_ids ? 'FIELD(id, ' . implode (',', $picture_ids) . ')' : 'id DESC',
        'conditions' => $conditions
      ));

    return $this->add_tab ('標註 ' . $tag->name . ' 的照片', array ('href' => base_url ('admin', 'picture_tags', $tag->id, 'pictures'), 'index' => 3))
                ->add_tab ('新增標註 ' . $tag->name . ' 的照片', array ('href' => base_url ('admin', 'picture_tags', $tag->id, 'pictures', 'add'), 'index' => 4))
                ->set_tab_index (3)
                ->set_subtitle ('標註 ' . $tag->name . ' 的照片')
                ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', 'picture_tags', $tag->id, 'pictures', 'sort')))
                ->load_view (array (
                    'tag' => $tag,
                    'pictures' => $pictures,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add ($id) {
    if (!($id && ($tag = PictureTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'picture_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('標註 ' . $tag->name . ' 的照片', array ('href' => base_url ('admin', 'picture_tags', $tag->id, 'pictures'), 'index' => 3))
                ->add_tab ('新增標註 ' . $tag->name . ' 的照片', array ('href' => base_url ('admin', 'picture_tags', $tag->id, 'pictures', 'add'), 'index' => 4))
                ->set_tab_index (4)
                ->set_subtitle ('新增標註 ' . $tag->name . ' 的照片')
                ->load_view (array (
                    'tag' => $tag,
                    'posts' => $posts
                  ));
  }
  public function create ($id) {
    if (!($id && ($tag = PictureTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'picture_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);
    $name = OAInput::file ('name');

    if (!($name || $posts['url']))
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', 'add'), array (
          '_flash_message' => '請選擇照片(gif、jpg、png)檔案，或提供照片網址!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_pictures_posts ($posts))
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['pv'] = 0;
    $posts['name'] = '';
    $posts['user_id'] = User::current ()->id;

    $create = Picture::transaction (function () use ($posts, $name, $tag) {
      if (!(verifyCreateOrm ($picture = Picture::create (array_intersect_key ($posts, Picture::table ()->columns))) && (($name && $picture->name->put ($name)) || ($posts['url'] && $picture->name->put_url ($posts['url'])))))
        return false;

      if (!verifyCreateOrm ($mapping = PictureTagMapping::create (array (
          'picture_id' => $picture->id,
          'picture_tag_id' => $tag->id,
          'sort' => PictureTagMapping::count (array ('conditions' => array ('picture_tag_id = ?', $tag->id)))
        ))))
        return false;

      if ($posts['sources'])
        foreach ($posts['sources'] as $source)
          if (!verifyCreateOrm (PictureSource::create (array (
                                  'picture_id' => $picture->id,
                                  'title' => $source['title'],
                                  'href' => $source['href'],
                                  'sort' => $i = isset ($i) ? ++$i : 0
                                ))))
            return false;

      delay_job ('pictures', 'update_color_dimension', array ('id' => $picture->id));
      return true;
    });

    if (!$create)
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures'), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit ($tag_id, $picture_id) {
    if (!($tag_id && ($tag = PictureTag::find_by_id ($tag_id))))
      return redirect_message (array ('admin', 'picture_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));
    if (!($picture_id && ($picture = Picture::find_by_id ($picture_id))))
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('標註 ' . $tag->name . ' 的照片', array ('href' => base_url ('admin', 'picture_tags', $tag->id, 'pictures'), 'index' => 3))
                ->add_tab ('新增標註 ' . $tag->name . ' 的照片', array ('href' => base_url ('admin', 'picture_tags', $tag->id, 'pictures', 'add'), 'index' => 4))
                ->add_tab ('編輯 ' . $picture->title . ' 照片', array ('href' => base_url ('admin', 'picture_tags', $tag->id, 'pictures', $picture->id, 'edit'), 'index' => 5))
                ->set_tab_index (5)
                ->set_subtitle ('編輯 ' . $picture->title . ' 照片')
                ->load_view (array (
                    'posts' => $posts,
                    'tag' => $tag,
                    'picture' => $picture,
                  ));
  }
  public function update ($tag_id, $picture_id) {
    if (!($tag_id && ($tag = PictureTag::find_by_id ($tag_id))))
      return redirect_message (array ('admin', 'picture_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!($picture_id && ($picture = Picture::find_by_id ($picture_id))))
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', $picture->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);
    $name = OAInput::file ('name');

    if (!((string)$picture->name || $name || $posts['url']))
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', $picture->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_pictures_posts ($posts))
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', $picture->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $picture->table ()->columns))
      foreach ($columns as $column => $value)
        $picture->$column = $value;

    $update = Picture::transaction (function () use ($picture, $posts, $name) {
      if ($picture->sources)
        foreach ($picture->sources as $source)
          if (!$source->destroy ())
            return false;

      if ($posts['sources'])
        foreach ($posts['sources'] as $source)
          if (!verifyCreateOrm (PictureSource::create (array (
                                  'picture_id' => $picture->id,
                                  'title' => $source['title'],
                                  'href' => $source['href'],
                                  'sort' => $i = isset ($i) ? ++$i : 0
                                ))))
            return false;
      
      if (!$picture->save ())
        return false;

      if ($name && !$picture->name->put ($name))
        return false;

      if ($posts['url'] && !$picture->name->put_url ($posts['url']))
        return false;

      if ($name || $posts['url'])
        delay_job ('pictures', 'update_color_dimension', array ('id' => $picture->id));
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures', $picture->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures'), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy ($tag_id, $picture_id) {
    if (!($tag_id && ($tag = PictureTag::find_by_id ($tag_id))))
      return redirect_message (array ('admin', 'picture_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!($picture_id && ($picture = Picture::find_by_id ($picture_id))))
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $delete = Picture::transaction (function () use ($picture) {
      return $picture->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures'), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', 'picture_tags', $tag->id, 'pictures'), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  public function sort ($id) {
    if (!$this->input->is_ajax_request ())
      return show_404 ();
    
    if (!($id && ($tag = PictureTag::find_by_id ($id))))
      return $this->output_json (array ('status' => false));

    if (!(($id = trim (OAInput::post ('id'))) && ($sort = trim (OAInput::post ('sort'))) && in_array ($sort, array ('up', 'down')) && ($mapping = PictureTagMapping::find_by_picture_id ($id))))
      return $this->output_json (array ('status' => false));

    PictureTagMapping::addConditions ($conditions, 'picture_tag_id = ?', $tag->id);
    $total = PictureTagMapping::count (array ('conditions' => $conditions));

    switch ($sort) {
      case 'up':
        $sort = $mapping->sort;
        $mapping->sort = $mapping->sort + 1 >= $total ? 0 : $mapping->sort + 1;
        break;

      case 'down':
        $sort = $mapping->sort;
        $mapping->sort = $mapping->sort - 1 < 0 ? $total - 1 : $mapping->sort - 1;
        break;
    }

    PictureTagMapping::addConditions ($conditions, 'sort = ?', $mapping->sort);

    $update = PictureTagMapping::transaction (function () use ($conditions, $mapping, $sort) {
      if (($next = PictureTagMapping::find ('one', array ('conditions' => $conditions))) && (($next->sort = $sort) || true))
        if (!$next->save ()) return false;
      if (!$mapping->save ()) return false;

      return true;
    });
    return $this->output_json (array ('status' => $update));
  }
  private function _validation_pictures_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';
    if (!(isset ($posts['keywords']) && ($posts['keywords'] = trim ($posts['keywords']))))
      return '沒有填寫關鍵字！';
    if (!(isset ($posts['description']) && ($posts['description'] = trim ($posts['description']))))
      $posts['description'] = '';

    $posts['sources'] = isset ($posts['sources']) && ($posts['sources'] = array_filter (array_map (function ($source) {
          $return = array (
              'title' => trim ($source['title']),
              'href' => trim ($source['href'])
            );
          return $return['href'] ? $return : null;
        }, $posts['sources']))) ? $posts['sources'] : array ();
    return '';
  }
}