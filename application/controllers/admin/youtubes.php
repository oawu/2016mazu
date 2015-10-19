<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Youtubes extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    $this->add_tab ('影片列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('新增影片', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 2));
  }

  public function index ($offset = 0) {
    $columns = array ('title' => 'string', 'keywords' => 'string');
    $configs = array ('admin', $this->get_class (), '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Youtube', OAInput::get ())));

    $limit = 25;
    $total = Youtube::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $youtubes = Youtube::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'include' => array ('mappings'),
        'order' => 'id DESC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('影片列表')
                ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', $this->get_class (), 'sort')))
                ->load_view (array (
                    'youtubes' => $youtubes,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);
    
    return $this->set_tab_index (2)
                ->set_subtitle ('新增影片')
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

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['pv'] = 0;
    $posts['cover'] = '';
    $posts['user_id'] = User::current ()->id;

    $create = Youtube::transaction (function () use ($posts) {
      if (!(verifyCreateOrm ($youtube = Youtube::create (array_intersect_key ($posts, Youtube::table ()->columns))) && $youtube->cover->put_url ($youtube->bigger_youtube_image_urls ())))
        return false;

      if ($posts['tag_ids'] && ($tags = YoutubeTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids'])))))
        foreach ($tags as $tag)
          if (!verifyCreateOrm ($mapping = YoutubeTagMapping::create (array (
              'youtube_id' => $youtube->id,
              'youtube_tag_id' => $tag->id,
              'sort' => YoutubeTagMapping::count (array ('conditions' => array ('youtube_tag_id = ?', $tag->id)))
            ))))
            return false;

      if ($posts['sources'])
        foreach ($posts['sources'] as $source)
          if (!verifyCreateOrm (YoutubeSource::create (array (
                                  'youtube_id' => $youtube->id,
                                  'title' => $source['title'],
                                  'href' => $source['href'],
                                  'sort' => $i = isset ($i) ? ++$i : 0
                                ))))
            return false;

      delay_job ('youtubes', 'update_cover_color', array ('id' => $youtube->id));
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
    if (!($id && ($youtube = Youtube::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯 ' . $youtube->title . ' 影片', array ('href' => base_url ('admin', $this->get_class (), 'add'), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯 ' . $youtube->title . ' 影片')
                ->load_view (array (
                    'posts' => $posts,
                    'youtube' => $youtube,
                  ));
  }
  public function update ($id) {
    if (!($id && ($youtube = Youtube::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->get_class (), $youtube->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->get_class (), $youtube->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $is_update = $youtube->vid != $posts['vid'];

    if ($columns = array_intersect_key ($posts, $youtube->table ()->columns))
      foreach ($columns as $column => $value)
        $youtube->$column = $value;

    $update = Youtube::transaction (function () use ($youtube, $posts, $is_update) {
      $ori_ids = column_array ($youtube->mappings, 'youtube_tag_id');

      if (($del_ids = array_diff ($ori_ids, $posts['tag_ids'])) && ($tags = YoutubeTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $del_ids)))))
        foreach ($tags as $tag)
          if (!$tag->destroy ())
            return false;

      if (($add_ids = array_diff ($posts['tag_ids'], $ori_ids)) && ($tags = YoutubeTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $add_ids)))))
        foreach ($tags as $tag)
          if (!verifyCreateOrm ($mapping = YoutubeTagMapping::create (array (
              'youtube_id' => $youtube->id,
              'youtube_tag_id' => $tag->id,
              'sort' => YoutubeTagMapping::count (array ('conditions' => array ('youtube_tag_id = ?', $tag->id)))
            ))))
            return false;

      if ($youtube->sources)
        foreach ($youtube->sources as $source)
          if (!$source->destroy ())
            return false;

      if ($posts['sources'])
        foreach ($posts['sources'] as $source)
          if (!verifyCreateOrm (YoutubeSource::create (array (
                                  'youtube_id' => $youtube->id,
                                  'title' => $source['title'],
                                  'href' => $source['href'],
                                  'sort' => $i = isset ($i) ? ++$i : 0
                                ))))
            return false;

      if (!$youtube->save ())
        return false;

      if ($is_update && !$youtube->cover->put_url ($youtube->bigger_youtube_image_urls ()))
        return false;

      if ($is_update)
        delay_job ('youtubes', 'update_cover_color', array ('id' => $youtube->id));
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->get_class (), $youtube->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', $this->get_class ()), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy ($id) {
    if (!($id && ($youtube = Youtube::find_by_id ($id))))
      return redirect_message (array ('admin', $this->get_class ()), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $delete = Youtube::transaction (function () use ($youtube) {
      return $youtube->destroy ();
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
    if (!(isset ($posts['url']) && ($posts['url'] = trim ($posts['url']))))
      return '沒有填寫影片網址！';

    if (preg_match ('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $posts['url'], $match))
      $posts['vid'] = $match[1];
    else
      return '找不到影片 ID！';

    if (!(isset ($posts['keywords']) && ($posts['keywords'] = trim ($posts['keywords']))))
      return '沒有填寫關鍵字！';
    if (!(isset ($posts['description']) && ($posts['description'] = trim ($posts['description']))))
      $posts['description'] = '';
    if (!(isset ($posts['tag_ids']) && ($posts['tag_ids'] = array_filter (array_map ('trim', $posts['tag_ids'])))))
      $posts['tag_ids'] = array ();
    
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
