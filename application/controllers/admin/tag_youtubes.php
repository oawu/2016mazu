<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Tag_youtubes extends Admin_controller {
  private $tag_class = null;
  private $uri_1     = null;
  private $uri_2     = null;
  private $tag       = null;
  private $youtube    = null;

  public function __construct () {
    parent::__construct ();

    $this->tag_class = 'youtube_tags';
    $this->uri_1     = 'tag';
    $this->uri_2     = 'youtubes';

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = YoutubeTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'youtube-tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->youtube = Youtube::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_param ('class', $this->tag_class)
         ->add_tab ('標籤列表', array ('href' => base_url (array ('admin', 'youtube-tags')), 'index' => 1))
         ->add_tab ($this->tag->name . '內的影音列表', array ('href' => base_url ('admin', $this->uri_1, $this->tag->id, $this->uri_2), 'index' => 2))
         ->add_tab ('新增' . $this->tag->name . '的影音', array ('href' => base_url ('admin', $this->uri_1, $this->tag->id, $this->uri_2, 'add'), 'index' => 3))
         ->add_param ('uri_1', $this->uri_1)
         ->add_param ('uri_2', $this->uri_2)
         ->add_param ('tag', $this->tag)
         ;
  }

  public function index ($tag_id, $offset = 0) {
    $columns = array (
        array ('key' => 'user_id',    'title' => '作者',    'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
        array ('key' => 'title',      'title' => '標題',    'sql' => 'title LIKE ?'), 
        array ('key' => 'keywords',   'title' => '關鍵字',  'sql' => 'keywords LIKE ?'), 
        array ('key' => 'content',    'title' => '內容',    'sql' => 'content LIKE ?'), 
        array ('key' => 'pv_bigger',  'title' => 'PV 大於', 'sql' => 'pv >= ?'), 
        array ('key' => 'pv_smaller', 'title' => 'PV 小於', 'sql' => 'pv <= ?'), 
      );

    $configs = array ('admin', $this->uri_2, $this->tag->id,  $this->uri_2, '%s');
    $conditions = conditions ($columns, $configs);
    Youtube::addConditions ($conditions, 'destroy_user_id IS NULL');

    if ($youtube_ids = column_array (YoutubeTagMapping::find ('all', array ('select' => 'youtube_id', 'order' => 'youtube_id DESC', 'conditions' => array ('youtube_tag_id = ?', $this->tag->id))), 'youtube_id'))
      Youtube::addConditions ($conditions, 'id IN (?)', $youtube_ids);
    else
      Youtube::addConditions ($conditions, 'id = ?', -1);

    $limit = 25;
    $total = Youtube::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $youtubes = Youtube::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (2)
                ->set_subtitle ($this->tag->name . '內的影音列表')
                ->add_hidden (array ('id' => 'is_enabled_url', 'value' => base_url ('admin', $this->uri_1, $this->tag->id, $this->uri_2, 'is_enabled')))
                ->load_view (array (
                    'youtubes' => $youtubes,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }

  public function add ($tag_id) {
    $posts = Session::getData ('posts', true);

    $posts['sources'] = isset ($posts['sources']) && $posts['sources'] ? array_slice (array_filter ($posts['sources'], function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }), 0) : array ();

    return $this->set_tab_index (3)
                ->set_subtitle ('新增' . $this->tag->name . '的影音')
                ->load_view (array (
                    'tags' => YoutubeTag::all (),
                    'posts' => $posts,
                  ));
  }

  public function create ($tag_id) {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2, 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['content'] = OAInput::post ('content', false);

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2, 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['pv'] = 0;
    $posts['cover'] = '';
    $posts['user_id'] = User::current ()->id;

    $youtube = null;
    $create = Youtube::transaction (function () use (&$youtube, $posts) {
      if (!verifyCreateOrm ($youtube = Youtube::create (array_intersect_key ($posts, Youtube::table ()->columns))))
        return false;

      if (!$youtube->cover->put_url ($youtube->bigger_youtube_image_urls ()))
        return false;

      return true;
    });

    if (!($create && $youtube))
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2, 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    if ($posts['tag_ids'] && ($tag_ids = column_array (YoutubeTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id')))
      foreach ($tag_ids as $tag_id)
        YoutubeTagMapping::transaction (function () use ($tag_id, $youtube) {
          return verifyCreateOrm (YoutubeTagMapping::create (array_intersect_key (array (
            'youtube_id' => $youtube->id,
            'youtube_tag_id' => $tag_id,
            ), YoutubeTagMapping::table ()->columns)));
        });

    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        YoutubeSource::transaction (function () use ($i, $source, $youtube) {
          return verifyCreateOrm (YoutubeSource::create (array_intersect_key (array_merge ($source, array (
            'youtube_id' => $youtube->id,
            )), YoutubeSource::table ()->columns)));
        });

    delay_job ('youtubes', 'update_cover_color_and_dimension', array ('id' => $youtube->id));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    $posts['sources'] = isset ($posts['sources']) && $posts['sources'] ? array_slice (array_filter ($posts['sources'], function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }), 0) : ($this->youtube->sources ? array_filter (array_map (function ($source) {return array ('title' => $source->title, 'href' => $source->href);}, $this->youtube->sources), function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }) : array ());

    return $this->add_tab ('編輯影音', array ('href' => base_url ('admin', $this->uri_1, $this->tag->id, $this->uri_2, 'edit', $this->youtube->id), 'index' => 4))
                ->set_tab_index (4)
                ->set_subtitle ('編輯影音')
                ->load_view (array (
                    'posts' => $posts,
                    'tags' => YoutubeTag::all (),
                    'youtube' => $this->youtube
                  ));
  }

  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2, $this->youtube->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['content'] = OAInput::post ('content', false);

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2, $this->youtube->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $is_update = $this->youtube->vid != $posts['vid'];

    if ($columns = array_intersect_key ($posts, $this->youtube->table ()->columns))
      foreach ($columns as $column => $value)
        $this->youtube->$column = $value;
    
    $youtube = $this->youtube;
    $update = Youtube::transaction (function () use ($youtube, $posts, $is_update) {
      if (!$youtube->save ())
        return false;

      if ($is_update && !$youtube->cover->put_url ($youtube->bigger_youtube_image_urls ()))
        return false;
      
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2, $this->youtube->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    $ori_ids = column_array ($youtube->mappings, 'youtube_tag_id');
    if (($del_ids = array_diff ($ori_ids, $posts['tag_ids'])) && ($mappings = YoutubeTagMapping::find ('all', array ('select' => 'id, youtube_tag_id', 'conditions' => array ('youtube_id = ? AND youtube_tag_id IN (?)', $youtube->id, $del_ids)))))
      foreach ($mappings as $mapping)
        YoutubeTagMapping::transaction (function () use ($mapping) {
          return $mapping->destroy ();
        });

    if (($add_ids = array_diff ($posts['tag_ids'], $ori_ids)) && $tag_ids = column_array (YoutubeTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $add_ids))), 'id'))
      foreach ($tag_ids as $tag_id)
        YoutubeTagMapping::transaction (function () use ($tag_id, $youtube) {
          return verifyCreateOrm (YoutubeTagMapping::create (Array_intersect_key (array (
              'youtube_tag_id' => $tag_id,
              'youtube_id' => $youtube->id,
            ), YoutubeTagMapping::table ()->columns)));
        });

    if ($youtube->sources)
      foreach ($youtube->sources as $source)
        YoutubeSource::transaction (function () use ($source) {
          return $source->destroy ();
        });

    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        YoutubeSource::transaction (function () use ($i, $source, $youtube) {
          return verifyCreateOrm (YoutubeSource::create (array_intersect_key (array_merge ($source, array (
            'youtube_id' => $youtube->id,
            )), YoutubeSource::table ()->columns)));
        });

    if ($is_update)
      delay_job ('youtubes', 'update_cover_color_and_dimension', array ('id' => $youtube->id));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2), array (
        '_flash_message' => '更新成功！'
      ));
  }

  public function destroy () {
    if (!User::current ()->id)
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2), array (
          '_flash_message' => '刪除失敗！',
        ));

    $posts = array (
        'destroy_user_id' => User::current ()->id
      );

    $youtube = $this->youtube;
    if ($columns = array_intersect_key ($posts, $youtube->table ()->columns))
      foreach ($columns as $column => $value)
        $youtube->$column = $value;

    $delete = Youtube::transaction (function () use ($youtube) {
      return $youtube->save ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2), array (
          '_flash_message' => '刪除失敗！',
        ));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1, $this->tag->id, $this->uri_2), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  public function is_enabled ($tag_id, $id = 0) {
    if (!($id && ($youtube = Youtube::find_by_id ($id, array ('select' => 'id, is_enabled, updated_at')))))
      return $this->output_json (array ('status' => false, 'message' => '當案不存在，或者您的權限不夠喔！'));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_is_enabled_posts ($posts))
      return $this->output_json (array ('status' => false, 'message' => $msg, 'content' => Youtube::$isIsEnabledNames[$youtube->is_enabled]));

    if ($columns = array_intersect_key ($posts, $youtube->table ()->columns))
      foreach ($columns as $column => $value)
        $youtube->$column = $value;

    $update = Youtube::transaction (function () use ($youtube) { return $youtube->save (); });

    if (!$update)
      return $this->output_json (array ('status' => false, 'message' => '更新失敗！', 'content' => Youtube::$isIsEnabledNames[$youtube->is_enabled]));

    $this->_clean ();
    return $this->output_json (array ('status' => true, 'message' => '更新成功！', 'content' => Youtube::$isIsEnabledNames[$youtube->is_enabled]));
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

    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';

    // $posts['content'] = str_replace ('alt=""', 'alt="' . str_replace ('"', '', $posts['title']) . ' - ' . Cfg::setting ('site', 'title') . '"', $posts['content']);

    if (!(isset ($posts['tag_ids']) && ($posts['tag_ids'] = array_filter (array_map ('trim', $posts['tag_ids']))) && ($posts['tag_ids'] = column_array (YoutubeTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id'))))
      $posts['tag_ids'] = array ();

    $posts['sources'] = isset ($posts['sources']) && ($posts['sources'] = array_filter (array_map (function ($source) {
          $return = array (
              'title' => trim ($source['title']),
              'href' => trim ($source['href']));
          return $return['href'] ? $return : null;
        }, $posts['sources']))) ? $posts['sources'] : array ();

    if (!(isset ($posts['is_enabled']) && is_numeric ($posts['is_enabled'] = trim ($posts['is_enabled'])) && in_array ($posts['is_enabled'], array_keys (Youtube::$isIsEnabledNames))))
      $posts['is_enabled'] = Youtube::NO_ENABLED;

    return '';
  }
  private function _validation_is_enabled_posts (&$posts) {
    if (!(isset ($posts['is_enabled']) && is_numeric ($posts['is_enabled']) && in_array ($posts['is_enabled'], array_keys (Youtube::$isIsEnabledNames))))
      return '參數錯誤！';
    return '';
  }
  private function _clean () {
    $this->output->delete_all_cache ();
  }
}
