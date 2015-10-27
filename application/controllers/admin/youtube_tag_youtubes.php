<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Youtube_tag_youtubes extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    $this->add_param ('class', 'youtube_tags')
         ->add_tab ('標籤列表', array ('href' => base_url ('admin', 'youtube_tags'), 'index' => 1))
         ->add_tab ('新增標籤', array ('href' => base_url ('admin', 'youtube_tags', 'add'), 'index' => 2));
  }

  public function index ($id, $offset = 0) {
    if (!($id && ($tag = YoutubeTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'youtube_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $columns = array ('title' => 'string', 'keywords' => 'string');
    $configs = array ('admin', 'youtube_tags', $tag->id, 'youtubes', '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Youtube', OAInput::get ())));
    
    if ($youtube_ids = column_array (YoutubeTagMapping::find ('all', array ('select' => 'youtube_id', 'order' => 'sort DESC', 'conditions' => array ('youtube_tag_id = ?', $tag->id))), 'youtube_id'))
      Youtube::addConditions ($conditions, 'id IN (?)', $youtube_ids);
    else
      Youtube::addConditions ($conditions, 'id = ?', -1);

    $limit = 25;
    $total = Youtube::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $youtubes = Youtube::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => $youtube_ids ? 'FIELD(id, ' . implode (',', $youtube_ids) . ')' : 'id DESC',
        'conditions' => $conditions
      ));

    return $this->add_tab ('標註 ' . $tag->name . ' 的影片', array ('href' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes'), 'index' => 3))
                ->add_tab ('新增標註 ' . $tag->name . ' 的影片', array ('href' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes', 'add'), 'index' => 4))
                ->set_tab_index (3)
                ->set_subtitle ('標註 ' . $tag->name . ' 的影片')
                ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes', 'sort')))
                ->load_view (array (
                    'tag' => $tag,
                    'youtubes' => $youtubes,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add ($id) {
    if (!($id && ($tag = YoutubeTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'youtube_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('標註 ' . $tag->name . ' 的影片', array ('href' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes'), 'index' => 3))
                ->add_tab ('新增標註 ' . $tag->name . ' 的影片', array ('href' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes', 'add'), 'index' => 4))
                ->set_tab_index (4)
                ->set_subtitle ('新增標註 ' . $tag->name . ' 的影片')
                ->load_view (array (
                    'tag' => $tag,
                    'posts' => $posts
                  ));
  }
  public function create ($id) {
    if (!($id && ($tag = YoutubeTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'youtube_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes', 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);

    if ($msg = $this->_validation_youtubes_posts ($posts))
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes', 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['pv'] = 0;
    $posts['cover'] = '';
    $posts['user_id'] = User::current ()->id;

    $create = Youtube::transaction (function () use ($posts, $tag) {
      if (!(verifyCreateOrm ($youtube = Youtube::create (array_intersect_key ($posts, Youtube::table ()->columns))) && $youtube->cover->put_url ($youtube->bigger_youtube_image_urls ())))
        return false;

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
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes', 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes'), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit ($tag_id, $youtube_id) {
    if (!($tag_id && ($tag = YoutubeTag::find_by_id ($tag_id))))
      return redirect_message (array ('admin', 'youtube_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));
    if (!($youtube_id && ($youtube = Youtube::find_by_id ($youtube_id))))
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('標註 ' . $tag->name . ' 的影片', array ('href' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes'), 'index' => 3))
                ->add_tab ('新增標註 ' . $tag->name . ' 的影片', array ('href' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes', 'add'), 'index' => 4))
                ->add_tab ('編輯 ' . $youtube->title . ' 影片', array ('href' => base_url ('admin', 'youtube_tags', $tag->id, 'youtubes', $youtube->id, 'edit'), 'index' => 5))
                ->set_tab_index (5)
                ->set_subtitle ('編輯 ' . $youtube->title . ' 影片')
                ->load_view (array (
                    'posts' => $posts,
                    'tag' => $tag,
                    'youtube' => $youtube,
                  ));
  }
  public function update ($tag_id, $youtube_id) {
    if (!($tag_id && ($tag = YoutubeTag::find_by_id ($tag_id))))
      return redirect_message (array ('admin', 'youtube_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!($youtube_id && ($youtube = Youtube::find_by_id ($youtube_id))))
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes', $youtube->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);

    if ($msg = $this->_validation_youtubes_posts ($posts))
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes', $youtube->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $is_update = $youtube->vid != $posts['vid'];

    if ($columns = array_intersect_key ($posts, $youtube->table ()->columns))
      foreach ($columns as $column => $value)
        $youtube->$column = $value;

    $update = Youtube::transaction (function () use ($youtube, $posts, $is_update) {
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
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes', $youtube->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes'), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy ($tag_id, $youtube_id) {
    if (!($tag_id && ($tag = YoutubeTag::find_by_id ($tag_id))))
      return redirect_message (array ('admin', 'youtube_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (!($youtube_id && ($youtube = Youtube::find_by_id ($youtube_id))))
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $delete = Youtube::transaction (function () use ($youtube) {
      return $youtube->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes'), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', 'youtube_tags', $tag->id, 'youtubes'), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  public function sort ($id) {
    if (!$this->input->is_ajax_request ())
      return show_404 ();
    
    if (!($id && ($tag = YoutubeTag::find_by_id ($id))))
      return $this->output_json (array ('status' => false));

    if (!(($id = trim (OAInput::post ('id'))) && ($sort = trim (OAInput::post ('sort'))) && in_array ($sort, array ('up', 'down')) && ($mapping = YoutubeTagMapping::find_by_youtube_id ($id))))
      return $this->output_json (array ('status' => false));

    YoutubeTagMapping::addConditions ($conditions, 'youtube_tag_id = ?', $tag->id);
    $total = YoutubeTagMapping::count (array ('conditions' => $conditions));

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

    YoutubeTagMapping::addConditions ($conditions, 'sort = ?', $mapping->sort);

    $update = YoutubeTagMapping::transaction (function () use ($conditions, $mapping, $sort) {
      if (($next = YoutubeTagMapping::find ('one', array ('conditions' => $conditions))) && (($next->sort = $sort) || true))
        if (!$next->save ()) return false;
      if (!$mapping->save ()) return false;

      return true;
    });
    return $this->output_json (array ('status' => $update));
  }
  private function _validation_youtubes_posts (&$posts) {
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
