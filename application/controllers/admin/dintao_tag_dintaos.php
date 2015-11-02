<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintao_tag_dintaos extends Admin_controller {
  private $tag = null;
  private $dintao = null;

  public function __construct () {
    parent::__construct ();

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = DintaoTag::find_by_id ($id))))
      return redirect_message (array ('admin', 'dintao_tags'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->dintao = Dintao::find_by_id ($id))))
        return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_param ('class', 'dintao_tags')
         ->add_tab ('標籤列表', array ('href' => base_url ('admin', 'dintao_tags'), 'index' => 1))
         ->add_tab ('新增標籤', array ('href' => base_url ('admin', 'dintao_tags', 'add'), 'index' => 2))
         ->add_tab ('標註 ' . $this->tag->name . ' 的陣頭', array ('href' => base_url ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), 'index' => 3))
         ->add_tab ('新增標註 ' . $this->tag->name . ' 的陣頭', array ('href' => base_url ('admin', 'dintao_tags', $this->tag->id, 'dintaos', 'add'), 'index' => 4))
         ;
  }

  public function index ($id, $offset = 0) {
    $columns = array ('title' => 'string', 'keywords' => 'string');
    $configs = array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Dintao', OAInput::get ())));
    
    if ($dintao_ids = column_array (DintaoTagMapping::find ('all', array ('select' => 'dintao_id', 'order' => 'sort DESC', 'conditions' => array ('dintao_tag_id = ?', $this->tag->id))), 'dintao_id'))
      Dintao::addConditions ($conditions, 'id IN (?)', $dintao_ids);
    else
      Dintao::addConditions ($conditions, 'id = ?', -1);

    $limit = 25;
    $total = Dintao::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $dintaos = Dintao::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => $dintao_ids ? 'FIELD(id, ' . implode (',', $dintao_ids) . ')' : 'id DESC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (3)
                ->set_subtitle ('標註 ' . $this->tag->name . ' 的陣頭')
                ->load_view (array (
                    'tag' => $this->tag,
                    'dintaos' => $dintaos,
                    'pagination' => $pagination,
                    'has_search' => array_filter ($columns),
                    'columns' => $columns
                  ));
  }
  public function add () {
    $posts = Session::getData ('posts', true);
    
    return $this->set_tab_index (4)
                ->set_subtitle ('新增標註 ' . $this->tag->name . ' 的陣頭')
                ->load_view (array (
                    'tag' => $this->tag,
                    'posts' => $posts
                  ));
  }
  public function create () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', 'add'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);
    $cover = OAInput::file ('cover');

    if (!($cover || $posts['url']))
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', 'add'), array (
          '_flash_message' => '請選擇陣頭(gif、jpg、png)檔案，或提供陣頭網址!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', 'add'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $posts['pv'] = 0;
    $posts['cover'] = '';
    $posts['user_id'] = User::current ()->id;

    $tag = $this->tag;
    $create = Dintao::transaction (function () use ($posts, $cover, $tag) {
      if (!(verifyCreateOrm ($dintao = Dintao::create (array_intersect_key ($posts, Dintao::table ()->columns))) && (($cover && $dintao->cover->put ($cover)) || ($posts['url'] && $dintao->cover->put_url ($posts['url'])))))
        return false;

      if (!verifyCreateOrm ($mapping = DintaoTagMapping::create (array (
          'dintao_id' => $dintao->id,
          'dintao_tag_id' => $tag->id,
          'sort' => DintaoTagMapping::count (array ('conditions' => array ('dintao_tag_id = ?', $tag->id)))
        ))))
        return false;

      if ($posts['sources'])
        foreach ($posts['sources'] as $source)
          if (!verifyCreateOrm (DintaoSource::create (array (
                                  'dintao_id' => $dintao->id,
                                  'title' => $source['title'],
                                  'href' => $source['href'],
                                  'sort' => $i = isset ($i) ? ++$i : 0
                                ))))
            return false;

      delay_job ('dintaos', 'update_cover_color', array ('id' => $dintao->id));
      return true;
    });

    if (!$create)
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    return $this->add_tab ('編輯 ' . $this->dintao->title . ' 陣頭', array ('href' => base_url ('admin', 'dintao_tags', $this->tag->id, 'dintaos', $this->dintao->id, 'edit'), 'index' => 5))
                ->set_tab_index (5)
                ->set_subtitle ('編輯 ' . $this->dintao->title . ' 陣頭')
                ->load_view (array (
                    'posts' => $posts,
                    'tag' => $this->tag,
                    'dintao' => $this->dintao,
                  ));
  }
  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', $this->dintao->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['description'] = OAInput::post ('description', false);
    $cover = OAInput::file ('cover');

    if (!((string)$this->dintao->cover || $cover || $posts['url']))
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', $this->dintao->id, 'edit'), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', $this->dintao->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->dintao->table ()->columns))
      foreach ($columns as $column => $value)
        $this->dintao->$column = $value;

    $dintao = $this->dintao;
    $update = Dintao::transaction (function () use ($dintao, $posts, $cover) {
      if ($dintao->sources)
        foreach ($dintao->sources as $source)
          if (!$source->destroy ())
            return false;

      if ($posts['sources'])
        foreach ($posts['sources'] as $source)
          if (!verifyCreateOrm (DintaoSource::create (array (
                                  'dintao_id' => $dintao->id,
                                  'title' => $source['title'],
                                  'href' => $source['href'],
                                  'sort' => $i = isset ($i) ? ++$i : 0
                                ))))
            return false;
      
      if (!$dintao->save ())
        return false;

      if ($cover && !$dintao->cover->put ($cover))
        return false;

      if ($posts['url'] && !$dintao->cover->put_url ($posts['url']))
        return false;

      if ($cover || $posts['url'])
        delay_job ('dintaos', 'update_cover_color', array ('id' => $dintao->id));
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos', $this->dintao->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
        '_flash_message' => '更新成功！'
      ));
  }
  public function destroy () {
    
    $dintao = $this->dintao;

    $delete = Dintao::transaction (function () use ($dintao) {
      return $dintao->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
        '_flash_message' => '刪除成功！'
      ));
  }
  public function sort ($id, $dintao_id, $sort) {
    if (!(in_array ($sort, array ('up', 'down')) && ($mapping = DintaoTagMapping::find_by_dintao_id_and_dintao_tag_id ($this->dintao->id, $this->tag->id))))
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
          '_flash_message' => '排序失敗！'
        ));

    DintaoTagMapping::addConditions ($conditions, 'dintao_tag_id = ?', $this->tag->id);
    $total = DintaoTagMapping::count (array ('conditions' => $conditions));

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

    DintaoTagMapping::addConditions ($conditions, 'sort = ?', $mapping->sort);

    $update = DintaoTagMapping::transaction (function () use ($conditions, $mapping, $sort) {
      if (($next = DintaoTagMapping::find ('one', array ('conditions' => $conditions))) && (($next->sort = $sort) || true))
        if (!$next->save ()) return false;
      if (!$mapping->save ()) return false;

      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
          '_flash_message' => '排序失敗！',
        ));
    return redirect_message (array ('admin', 'dintao_tags', $this->tag->id, 'dintaos'), array (
        '_flash_message' => '排序成功！'
      ));
  }
  private function _validation_posts (&$posts) {
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
