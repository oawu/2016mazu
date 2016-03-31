<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Baishatun_path_infos extends Admin_controller {
  private $uri_1 = null;
  private $info = null;

  public function __construct () {
    parent::__construct ();

    $this->uri_1 = 'baishatun-path-infos';

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->info = BaishatunPathInfo::find ('one', array ('conditions' => array ('id = ?', $id))))))
        return redirect_message (array ('admin', $this->uri_1), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->add_tab ('資訊列表', array ('href' => base_url ('admin', $this->uri_1), 'index' => 1))
         ->add_tab ('新增資訊', array ('href' => base_url ('admin', $this->uri_1, 'add'), 'index' => 2))
         ->add_param ('uri_1', $this->uri_1)
         ;
  }

  public function index ($offset = 0) {
    $columns = array (
        array ('key' => 'msgs', 'title' => '訊息', 'sql' => 'msgs LIKE ?'), 
      );

    $configs = array ('admin', $this->uri_1, '%s');
    $conditions = conditions ($columns, $configs);

    $limit = 25;
    $total = BaishatunPathInfo::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $infos = BaishatunPathInfo::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'conditions' => $conditions
      ));

    return $this->set_tab_index (1)
                ->set_subtitle ('資訊列表')
                ->load_view (array (
                    'infos' => $infos,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ));
  }

  public function add () {
    $posts = Session::getData ('posts', true);

    $posts['messages'] = isset ($posts['messages']) && $posts['messages'] ? array_slice (array_filter ($posts['messages'], function ($message) {
      return $message;
    }), 0) : array ();
    $p = render_cell ('baishatun_cell', 'api', 'BaishatunComPath', 0);
    $p = $p['p'];
    return $this->set_tab_index (2)
                ->set_subtitle ('新增資訊')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
                ->load_view (array (
                    'posts' => $posts,
                    'last' => BaishatunComPath::last (),
                    'points' => $p
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

    $info = null;
    $create = BaishatunPathInfo::transaction (function () use (&$info, $posts) {
      if (!verifyCreateOrm ($info = BaishatunPathInfo::create (array_intersect_key ($posts, BaishatunPathInfo::table ()->columns))))
        return false;
      return true;
    });

    if (!($create && $info))
      return redirect_message (array ('admin', $this->uri_1, 'add'), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    clean_cell ('baishatun_cell', '*');

    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit () {
    $posts = Session::getData ('posts', true);
    
    $posts['messages'] = isset ($posts['messages']) && $posts['messages'] ? array_slice (array_filter ($posts['messages'], function ($message) {
      return $message;
    }), 0) : ($this->info->msgs () ? array_filter (array_map (function ($message) {return $message;}, $this->info->msgs ()), function ($message) {
      return $message;
    }) : array ());

    $p = render_cell ('baishatun_cell', 'api', 'BaishatunComPath', 0);
    $p = $p['p'];

    return $this->add_tab ('編輯資訊', array ('href' => base_url ('admin', $this->uri_1, $this->info->id, 'edit'), 'index' => 3))
                ->set_tab_index (3)
                ->set_subtitle ('編輯資訊')
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
                ->load_view (array (
                    'posts' => $posts,
                    'points' => $p,
                    'last' => BaishatunComPath::last (),
                    'info' => $this->info
                  ));
  }

  public function update () {
    if (!$this->has_post ())
      return redirect_message (array ('admin', $this->uri_1, $this->info->id, 'edit'), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return redirect_message (array ('admin', $this->uri_1, $this->info->id, 'edit'), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    if ($columns = array_intersect_key ($posts, $this->info->table ()->columns))
      foreach ($columns as $column => $value)
        $this->info->$column = $value;
    
    $info = $this->info;
    $update = BaishatunPathInfo::transaction (function () use ($info, $posts) {
      if (!$info->save ())
        return false;
      return true;
    });

    if (!$update)
      return redirect_message (array ('admin', $this->uri_1, $this->info->id, 'edit'), array (
          '_flash_message' => '更新失敗！',
          'posts' => $posts
        ));

    clean_cell ('baishatun_cell', '*');

    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '更新成功！'
      ));
  }

  public function destroy () {
    if (!User::current ()->id)
      return redirect_message (array ('admin', $this->uri_1), array (
          '_flash_message' => '刪除失敗！',
        ));

    $info = $this->info;
    $delete = BaishatunPathInfo::transaction (function () use ($info) {
      return $info->destroy ();
    });

    if (!$delete)
      return redirect_message (array ('admin', $this->uri_1), array (
          '_flash_message' => '刪除失敗！',
        ));
    return redirect_message (array ('admin', $this->uri_1), array (
        '_flash_message' => '刪除成功！'
      ));
  }

  private function _validation_posts (&$posts) {
    if (!(isset ($posts['lat']) && ($posts['lat'] = trim ($posts['lat']))))
      return '沒有緯度，請點選地圖決定地點！';

    if (!(isset ($posts['lng']) && ($posts['lng'] = trim ($posts['lng']))))
      return '沒有經度，請點選地圖決定地點！';

    $posts['messages'] = isset ($posts['messages']) && ($posts['messages'] = array_filter (array_map (function ($message) {
          $return = trim ($message);
          return $return ? $return : null;
        }, $posts['messages']))) ? $posts['messages'] : array ();

    $posts['msgs'] = json_encode ($posts['messages']);

    return '';
  }
}
