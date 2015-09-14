<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Menus extends Admin_controller {

  public function sort () {
    if (!(($id = trim (OAInput::post ('id'))) && ($sort = trim (OAInput::post ('sort'))) && in_array ($sort, array ('up', 'down')) && ($menu = Menu::find_by_id ($id))))
      return $this->output_json (array ('status' => false));

    if ($menu->parent)
      Menu::addConditions ($conditions, 'menu_id = ?', $menu->menu_id);
    else
      Menu::addConditions ($conditions, 'menu_id IS NULL');

    $total = Menu::count (array ('conditions' => $conditions));

    switch ($sort) {
      case 'up':
        $sort = $menu->sort;
        $menu->sort = $menu->sort - 1 < 0 ? $total - 1 : $menu->sort - 1;
        break;

      case 'down':
        $sort = $menu->sort;
        $menu->sort = $menu->sort + 1 >= $total ? 0 : $menu->sort + 1;
        break;
    }

    Menu::addConditions ($conditions, 'sort = ?', $menu->sort);
    if ($next = Menu::find ('one', array ('conditions' => $conditions))) {
      $next->sort = $sort;
      if (!$next->save ())
        return $this->output_json (array ('status' => false));
    }

    if (!$menu->save ())
      return $this->output_json (array ('status' => false));

    return $this->output_json (array ('status' => true));
  }

  public function tree ($id = 0) {
    $menu = Menu::find_by_id ($id);
    $roles = column_array (Role::all (), 'name');
    $structs = $menu && ($structs = $menu->struct ($roles)) ? $structs['children'] : Menu::structs ($roles);

    $this->add_subtitle ($menu ? $menu->text . ' 下的結構' : '項目根下的結構')
         ->load_view (array (
        'menu' => $menu,
        'roles' => $roles,
        'structs' => $structs,
      ));
  }
  public function index ($parent_id = 0, $offset = 0) {
    $parent_menu = Menu::find_by_id ($parent_id);

    $columns = array ('id' => 'int', 'text' => 'string', 'href' => 'string', 'class' => 'string', 'method' => 'string');
    $configs = array ('admin', 'menus', $parent_menu ? $parent_menu->id : 0, '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Menu', OAInput::get ())));
    if ($parent_menu) Menu::addConditions ($conditions, 'menu_id = ?', $parent_menu->id);
    else Menu::addConditions ($conditions, 'menu_id IS NULL');

    $limit = 25;
    $total = Menu::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $menus = Menu::find ('all', array (
        'include' => array ('children'),
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'sort ASC',
        'conditions' => $conditions
      ));

    $this->add_subtitle ($parent_menu ? $parent_menu->text . ' 內項目列表' : '項目根列表')
         ->add_hidden (array ('id' => 'sort', 'value' => base_url ('admin', $this->get_class (), 'sort')))
         ->load_view (array (
        'menus' => $menus,
        'pagination' => $pagination,
        'has_search' => array_filter ($columns),
        'parent_menu' => $parent_menu,
        'columns' => $columns
      ));
  }
  private function _load_controllers_methods () {
    $this->load->helper ('directory');
    $controller_path = FCPATH . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
    $classes = array_filter (directory_map ($controller_path, 1), function ($class) {
          $path = FCPATH . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $class;
          return is_file ($path) && is_readable ($path) && (pathinfo ($path, PATHINFO_EXTENSION) == 'php');
        });
    return array_combine (array_map (function ($class) {
          return preg_replace ('/\.php$/', '', $class);
        }, $classes), array_map (function ($class) use ($controller_path) {
          $pattern = '/[\s\n]+public[\s\n]+function[\s\n]+(?P<methods>\S+)[\s\n]*\(/';
          preg_match_all ($pattern, $controller = read_file ($controller_path . $class), $controller);
          return $controller['methods'];
        }, $classes));
  }
  public function add ($id = 0) {
    $parent_menu = Menu::find_by_id ($id);
    $posts = Session::getData ('posts', true);
    $roles = $parent_menu ? $parent_menu->roles : Role::all ();
    $classes = $this->_load_controllers_methods ();

    $this->add_subtitle ($parent_menu ? $parent_menu->text . ' 內新增項目' : '新增項目')
         ->load_view (array (
        'posts' => $posts,
        'roles' => $roles,
        'classes' => $classes,
        'parent_menu' => $parent_menu
      ));
  }
  public function create ($id = 0) {
    $parent_menu = Menu::find_by_id ($id);

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'menus', 'add', $parent_menu ? $parent_menu->id : 0), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if($msg = $this->_validation_posts ($posts, $parent_menu))
      return redirect_message (array ('admin', 'menus', 'add', $parent_menu ? $parent_menu->id : 0), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $role_ids = isset($posts['role_ids']) ? $posts['role_ids'] : array ();
    unset ($posts['role_ids']);

    $conditions  = $parent_menu ? array ('menu_id = ?', $parent_menu->id) : array ('menu_id IS NULL');
    $posts['sort'] = Menu::count (array ('conditions' => $conditions));

    if (!verifyCreateOrm ($menu = Menu::create ($posts)))
      return redirect_message (array ('admin', 'menus', 'add', $parent_menu ? $parent_menu->id : 0), array (
          '_flash_message' => '新增失敗！',
          'posts' => $posts
        ));

    if ($role_ids)
      foreach ($role_ids as $role_id)
        MenuRole::create (array (
            'menu_id' => $menu->id,
            'role_id' => $role_id
          ));

    return redirect_message (array ('admin', 'menus', $parent_menu ? $parent_menu->id : 0), array (
        '_flash_message' => '新增成功！'
      ));
  }
  public function edit ($id) {
    if (!($menu = Menu::find_by_id ($id)))
      return redirect_message (array ('admin', 'menus'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    $posts = Session::getData ('posts', true);
    $roles = $menu->parent ? $menu->parent->roles : Role::all ();
    $classes = $this->_load_controllers_methods ();

    $this->add_subtitle ('修改項目')
         ->load_view (array (
        'posts' => $posts,
        'roles' => $roles,
        'classes' => $classes,
        'menu' => $menu
      ));
  }
  public function update ($id = 0) {
    if (!($menu = Menu::find_by_id ($id)))
      return redirect_message (array ('admin', 'menus'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    if (!$this->has_post ())
      return redirect_message (array ('admin', 'menus', 'edit', $menu->id), array (
          '_flash_message' => '非 POST 方法，錯誤的頁面請求。'
        ));

    $posts = OAInput::post ();

    if($msg = $this->_validation_posts ($posts, $menu->parent))
      return redirect_message (array ('admin', 'menus', 'edit', $menu->id), array (
          '_flash_message' => $msg,
          'posts' => $posts
        ));

    $role_ids = isset($posts['role_ids']) ? $posts['role_ids'] : array ();
    unset ($posts['role_ids']);

    $old_ids = column_array ($menu->menu_roles, 'role_id');

    if ($add_ids = array_diff ($role_ids, $old_ids))
      foreach ($add_ids as $role_id)
        MenuRole::create (array ('menu_id' => $menu->id, 'role_id' => $role_id));

    if ($del_ids = array_diff ($old_ids, $role_ids))
      foreach (MenuRole::find ('all', array ('select' => 'id', 'conditions' => array ('menu_id = ? AND role_id IN (?)', $menu->id, $del_ids))) as $menu_role)
        $menu_role->destroy ();

    foreach (array_keys (Menu::table ()->columns) as $column)
      if (isset ($posts[$column]))
        $menu->$column = $posts[$column];

    if (!$menu->save ())
      return redirect_message (array ('admin', 'menus', 'edit', $menu->id), array (
          '_flash_message' => '修改失敗！',
          'posts' => $posts
        ));

    return redirect_message (array ('admin', 'menus', $menu->parent ? $menu->parent->id : 0), array (
        '_flash_message' => '修改成功！'
      ));
  }
  public function destroy ($id) {
    if (!($menu = Menu::find_by_id ($id)))
      return redirect_message (array ('admin', 'menus'), array (
          '_flash_message' => '找不到指定的資料。'
        ));

    if (!$menu->destroy ())
      return redirect_message (array ('admin', 'menus', $menu->parent ? $menu->parent->id : 0), array (
          '_flash_message' => '刪除失敗。'
        ));

    return redirect_message (array ('admin', 'menus', $menu->parent ? $menu->parent->id : 0), array (
          '_flash_message' => '刪除成功。'
        ));
  }

  private function _validation_posts (&$posts, $parent_menu) {
    if (!(isset ($posts['text']) && ($posts['text'] = trim ($posts['text']))))
      return '沒有填寫文字！';

    if (!(isset ($posts['target']) && isset (menu::$targets[$posts['target'] = trim ($posts['target'])])))
      return '沒有選擇 Target！';

    if (isset ($posts['role_ids']) && $posts['role_ids'])
      if (!(($role_ids = column_array ($parent_menu ? $parent_menu->roles : Role::all (), 'id')) && ($posts['role_ids'] = array_intersect ($posts['role_ids'], $role_ids))))
        return '錯誤的角色 ID！';

    $posts['menu_id'] = $parent_menu ? $parent_menu->id : null;

    return '';
  }
}
