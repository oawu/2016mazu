<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintaos extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    $this->add_tab ('駕前陣頭', array ('href' => base_url ('admin', $this->get_class (), 1), 'index' => 1))
         ->add_tab ('地方陣頭', array ('href' => base_url ('admin', $this->get_class (), 2), 'index' => 2))
         ->add_tab ('其他介紹', array ('href' => base_url ('admin', $this->get_class (), 3), 'index' => 3));
  }
  public function index ($index = 1, $offset = 0) {
    $index = isset (Dintao::$types[$index]) ? $index : Dintao::TYPE_OFFICIAL;

    $columns = array ('id' => 'int', 'name' => 'string', 'content' => 'string');
    $configs = array ('admin', $this->get_class (), $index, '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Dintao', OAInput::get ())));
    Dintao::addConditions ($conditions, 'type = ?', $index);

    $limit = 25;
    $total = Dintao::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $dintaos = Dintao::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'sort ASC',
        'conditions' => $conditions
      ));

    $this->set_tab_index ($index)
         ->add_subtitle ('陣頭根列表')
         ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', $this->get_class (), 'sort')))
         ->load_view (array (
        'dintaos' => $dintaos,
        'pagination' => $pagination,
        'has_search' => array_filter ($columns),
        'columns' => $columns
      ));
  }
  public function add ($index = 1) {
    $index = isset (Dintao::$types[$index]) ? $index : Dintao::TYPE_OTHER;
    $posts = Session::getData ('posts', true);
    
    $this->set_tab_index ($index)
         ->load_view (array (
        'posts' => $posts
      ));
  }
  public function sort () {
    if (!(($id = trim (OAInput::post ('id'))) && ($sort = trim (OAInput::post ('sort'))) && in_array ($sort, array ('up', 'down')) && ($dintao = Dintao::find_by_id ($id))))
      return $this->output_json (array ('status' => false));

    Dintao::addConditions ($conditions, 'type = ?', $dintao->type);
    $total = Dintao::count (array ('conditions' => $conditions));

    switch ($sort) {
      case 'up':
        $sort = $dintao->sort;
        $dintao->sort = $dintao->sort - 1 < 0 ? $total - 1 : $dintao->sort - 1;
        break;

      case 'down':
        $sort = $dintao->sort;
        $dintao->sort = $dintao->sort + 1 >= $total ? 0 : $dintao->sort + 1;
        break;
    }

    Dintao::addConditions ($conditions, 'sort = ?', $dintao->sort);

    $update = Dintao::transaction (function () use ($conditions, $dintao, $sort) {
      if (($next = Dintao::find ('one', array ('conditions' => $conditions))) && (($next->sort = $sort) || true))
        if (!$next->save ()) return false;
      if (!$dintao->save ()) return false;

      return true;
    });
    return $this->output_json (array ('status' => $update));
  }
  public function create ($index = 1) {
    $index = isset (Dintao::$types[$index]) ? $index : Dintao::TYPE_OTHER;

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'dintaos', 'add', $index), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();
    $posts['content'] = OAInput::post ('content', false);
    $cover = OAInput::file ('cover');

    if (!$cover)
      return redirect_message (array ('admin', 'dintaos', 'add', $index), array (
          '_flash_message' => '請選擇圖片(gif、jpg、png)檔案!',
          'posts' => $posts
        ));

    if($msg = $this->_validation_posts ($posts, $index))
      return redirect_message (array ('admin', 'dintaos', 'add', $index), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $create = Dintao::transaction (function () use ($posts, $cover) {
      if (!(verifyCreateOrm ($dintao = Dintao::create (array_intersect_key ($posts, Dintao::table ()->columns))) && $dintao->cover->put ($cover)))
        return false;

      if ($posts['sources'])
        foreach ($posts['sources'] as $source)
          DintaoSource::create (array (
              'dintao_id' => $dintao->id,
              'title' => $source['title'],
              'href' => $source['href'],
              'sort' => $i = isset ($i) ? ++$i : 0
            ));
      return true;
    });

    if (!$create)
      return redirect_message (array ('admin', 'dintaos', 'add', $index), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));
    return redirect_message (array ('admin', 'dintaos', $index), array (
        '_flash_message' => '新增成功！'
      ));
  }
  private function _validation_posts (&$posts, $index) {
    if (!(isset ($posts['name']) && ($posts['name'] = trim ($posts['name']))))
      return '沒有填寫名稱！';
    if (!(isset ($posts['content']) && ($posts['content'] = trim ($posts['content']))))
      return '沒有填寫內容！';
    if (!(isset ($posts['keywords']) && ($posts['keywords'] = trim ($posts['keywords']))))
      return '沒有填寫關鍵字！';

    $posts['user_id'] = User::current ()->id;
    $posts['type'] = $index;
    $posts['cover'] = '';
    $posts['sort'] = Dintao::count (array ('conditions' => array ('type = ?', $index)));
    $posts['sources'] = isset ($posts['sources']) && $posts['sources'] ? $posts['sources'] : array ();
    return '';
  }
}
