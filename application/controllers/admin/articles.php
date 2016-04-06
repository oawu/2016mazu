<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Articles extends Admin_controller {
  private $uri_1 = null;
  private $article = null;

  public function __construct () {
    parent::__construct ();

    $this->uri_1 = 'articles';

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->article = Article::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('admin', $this->uri_1), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('文章列表', array ('href' => base_url ('admin', $this->uri_1), 'index' => 1))
         ->add_tab ('新增文章', array ('href' => base_url ('admin', $this->uri_1, 'add'), 'index' => 2))
         ->add_param ('uri_1', $this->uri_1)
         ;
  }

  public function index ($offset = 0) {
    $columns = array (
        array ('key' => 'user_id',    'title' => '作者',    'sql' => 'user_id = ?', 'select' => array_map (function ($user) { return array ('value' => $user->id, 'text' => $user->name);}, User::all (array ('select' => 'id, name')))),
        array ('key' => 'title',      'title' => '標題',    'sql' => 'title LIKE ?'), 
        array ('key' => 'keywords',   'title' => '關鍵字',  'sql' => 'keywords LIKE ?'), 
        array ('key' => 'content',    'title' => '內容',    'sql' => 'content LIKE ?'), 
        array ('key' => 'pv_bigger',  'title' => 'PV 大於', 'sql' => 'pv >= ?'), 
        array ('key' => 'pv_smaller', 'title' => 'PV 小於', 'sql' => 'pv <= ?'), 
      );

    $configs = array ('admin', $this->uri_1, '%s');
    $conditions = conditions ($columns, $configs);
    Article::addConditions ($conditions, 'destroy_user_id IS NULL');

    $limit = 25;
    $total = Article::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $articles = Article::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('文章列表')
                ->add_hidden (array ('id' => 'is_enabled_url', 'value' => base_url ('admin', $this->uri_1, 'is_enabled')))
                ->load_view (array (
                    'articles' => $articles,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }

  public function add () {
    $posts = Session::getData ('posts', true);

    $posts['sources'] = isset ($posts['sources']) && $posts['sources'] ? array_slice (array_filter ($posts['sources'], function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }), 0) : array ();

    return $this->set_tab_index (2)
                ->set_subtitle ('新增文章')
                ->load_view (array (
                    'posts' => $posts,
                    'tags' => ArticleTag::all ()
                  ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['content'] = OAInput::post ('content', false);
    $cover = OAInput::file ('cover');

    if (!($cover || $posts['url']))
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => '請選擇陣頭(gif、jpg、png)檔案，或提供陣頭網址!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['pv'] = 0;
    $posts['cover'] = '';
    $posts['user_id'] = User::current ()->id;

    $article = null;
    $create = Article::transaction (function () use (&$article, $posts, $cover) {
      if (!verifyCreateOrm ($article = Article::create (array_intersect_key ($posts, Article::table ()->columns))))
        return false;

      if (!(($cover && $article->cover->put ($cover)) || ($posts['url'] && $article->cover->put_url ($posts['url']))))
        return false;

      return true;
    });

    if (!($create && $article))
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    if ($posts['tag_ids'] && ($tag_ids = column_array (ArticleTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id')))
      foreach ($tag_ids as $tag_id)
        ArticleTagMapping::transaction (function () use ($tag_id, $article) {
          return verifyCreateOrm (ArticleTagMapping::create (array_intersect_key (array (
            'article_id' => $article->id,
            'article_tag_id' => $tag_id,
            ), ArticleTagMapping::table ()->columns)));
        });

    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        ArticleSource::transaction (function () use ($i, $source, $article) {
          return verifyCreateOrm (ArticleSource::create (array_intersect_key (array_merge ($source, array (
            'article_id' => $article->id,
            )), ArticleSource::table ()->columns)));
        });

    delay_job ('articles', 'update_cover_color_and_dimension', array ('id' => $article->id));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    $posts['sources'] = isset ($posts['sources']) && $posts['sources'] ? array_slice (array_filter ($posts['sources'], function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }), 0) : ($this->article->sources ? array_filter (array_map (function ($source) {return array ('title' => $source->title, 'href' => $source->href);}, $this->article->sources), function ($source) {
      return (isset ($source['title']) && $source['title']) || (isset ($source['href']) && $source['href']);
    }) : array ());

    return $this->add_tab ('編輯文章', array ('href' => base_url ('admin', $this->uri_1, $this->article->id, 'edit'), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯文章')
                ->load_view (array (
                    'posts' => $posts,
                    'tags' => ArticleTag::all (),
                    'article' => $this->article
                  ));
  }

  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->article->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['content'] = OAInput::post ('content', false);
    $cover = OAInput::file ('cover');

    if (!((string)$this->article->cover || $cover || $posts['url']))
      return redirect_message (array ('admin', $this->uri_1, $this->article->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->article->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->article->table ()->columns))
      foreach ($columns as $column => $value)
        $this->article->$column = $value;
    
    $article = $this->article;
    $update = Article::transaction (function () use ($article, $posts, $cover) {
      if (!$article->save ())
        return false;

      if ($cover && !$article->cover->put ($cover))
        return false;

      if ($posts['url'] && !$article->cover->put_url ($posts['url']))
        return false;
      
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->uri_1, $this->article->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    $ori_ids = column_array ($article->mappings, 'article_tag_id');
    if (($del_ids = array_diff ($ori_ids, $posts['tag_ids'])) && ($mappings = ArticleTagMapping::find ('all', array ('select' => 'id, article_tag_id', 'conditions' => array ('article_id = ? AND article_tag_id IN (?)', $article->id, $del_ids)))))
      foreach ($mappings as $mapping)
        ArticleTagMapping::transaction (function () use ($mapping) {
          return $mapping->destroy ();
        });

    if (($add_ids = array_diff ($posts['tag_ids'], $ori_ids)) && $tag_ids = column_array (ArticleTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $add_ids))), 'id'))
      foreach ($tag_ids as $tag_id)
        ArticleTagMapping::transaction (function () use ($tag_id, $article) {
          return verifyCreateOrm (ArticleTagMapping::create (Array_intersect_key (array (
              'article_tag_id' => $tag_id,
              'article_id' => $article->id,
            ), ArticleTagMapping::table ()->columns)));
        });

    if ($article->sources)
      foreach ($article->sources as $source)
        ArticleSource::transaction (function () use ($source) {
          return $source->destroy ();
        });

    if ($posts['sources'])
      foreach ($posts['sources'] as $i => $source)
        ArticleSource::transaction (function () use ($i, $source, $article) {
          return verifyCreateOrm (ArticleSource::create (array_intersect_key (array_merge ($source, array (
            'article_id' => $article->id,
            )), ArticleSource::table ()->columns)));
        });

    if ($cover || $posts['url'])
      delay_job ('articles', 'update_cover_color_and_dimension', array ('id' => $article->id));

    $this->_clean ();
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

    $article = $this->article;
    if ($columns = array_intersect_key ($posts, $article->table ()->columns))
      foreach ($columns as $column => $value)
        $article->$column = $value;

    $delete = Article::transaction (function () use ($article) {
      return $article->save ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->uri_1), array (
          '_flash_message' => '刪除失敗！',
        ));

    $this->_clean ();
    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  public function is_enabled ($id = 0) {
    if (!($id && ($article = Article::find_by_id ($id, array ('select' => 'id, is_enabled, updated_at')))))
      return $this->output_json (array ('status' => false, 'message' => '當案不存在，或者您的權限不夠喔！'));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_is_enabled_posts ($posts))
      return $this->output_json (array ('status' => false, 'message' => $msg, 'content' => Article::$isIsEnabledNames[$article->is_enabled]));

    if ($columns = array_intersect_key ($posts, $article->table ()->columns))
      foreach ($columns as $column => $value)
        $article->$column = $value;

    $update = Article::transaction (function () use ($article) { return $article->save (); });

    if (!$update)
      return $this->output_json (array ('status' => false, 'message' => '更新失敗！', 'content' => Article::$isIsEnabledNames[$article->is_enabled]));

    $this->_clean ();
    return $this->output_json (array ('status' => true, 'message' => '更新成功！', 'content' => Article::$isIsEnabledNames[$article->is_enabled]));
  }

  private function _validation_posts (&$posts) {
    if (!(isset ($posts['title']) && ($posts['title'] = trim ($posts['title']))))
      return '沒有填寫標題！';

    if (!(isset ($posts['keywords']) && ($posts['keywords'] = trim ($posts['keywords']))))
      return '沒有填寫關鍵字！';

    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';

    // $posts['content'] = str_replace ('alt=""', 'alt="' . str_replace ('"', '', $posts['title']) . ' - ' . Cfg::setting ('site', 'title') . '"', $posts['content']);

    if (!(isset ($posts['tag_ids']) && ($posts['tag_ids'] = array_filter (array_map ('trim', $posts['tag_ids']))) && ($posts['tag_ids'] = column_array (ArticleTag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $posts['tag_ids']))), 'id'))))
      $posts['tag_ids'] = array ();

    $posts['sources'] = isset ($posts['sources']) && ($posts['sources'] = array_filter (array_map (function ($source) {
          $return = array (
              'title' => trim ($source['title']),
              'href' => trim ($source['href']));
          return $return['href'] ? $return : null;
        }, $posts['sources']))) ? $posts['sources'] : array ();

    if (!(isset ($posts['is_enabled']) && is_numeric ($posts['is_enabled'] = trim ($posts['is_enabled'])) && in_array ($posts['is_enabled'], array_keys (Article::$isIsEnabledNames))))
      $posts['is_enabled'] = Article::NO_ENABLED;

    return '';
  }
  private function _validation_is_enabled_posts (&$posts) {
    if (!(isset ($posts['is_enabled']) && is_numeric ($posts['is_enabled']) && in_array ($posts['is_enabled'], array_keys (Article::$isIsEnabledNames))))
      return '參數錯誤！';
    return '';
  }
  private function _clean () {
    $this->output->delete_all_cache ();
  }
}
