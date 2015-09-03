{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Tags extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    // 取出所有的 tag
    $tags = Tag::all ();

    // 取出 session flash data
    $message = identity ()->get_session ('_flash_message', true);

    // load view
    $this->load_view (array (
        'tags' => $tags,
        'message' => $message
      ));
  }

  public function show ($id) {
    // 確認該 id 資料是否存在
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // load view
    $this->load_view (array (
        'tag' => $tag
      ));
  }

  public function add () {
    // 取出 session flash data
    $message = identity ()->get_session ('_flash_message', true);
    
    // load view
    $this->load_view (array (
        'message' => $message
      ));
  }

  public function create () {
    // 承接 POST 參數
    $name = trim ($this->input_post ('name'));

    // 檢查參數 使否格式正確
    if (!$name) {
      // 設定 session flash data，寫入失敗原因，然後導頁
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    // 設定 create 用的資料
    $params = array (
        'name' => $name
      );

    if (verifyCreateOrm ($tag = Tag::create ($params))) {
      // 設定 session flash data，寫入成功訊息，然後導頁
      identity ()->set_session ('_flash_message', '新增成功!', true);
      return redirect (array ($this->get_class (), 'index'), 'refresh');
    } else {
      // 設定 session flash data，寫入失敗原因，然後導頁
      identity ()->set_session ('_flash_message', '新增失敗!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }
  }

  public function edit ($id) {
    // 確認該 id 資料是否存在
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // 取出 session flash data
    $message = identity ()->get_session ('_flash_message', true);
    
    // load view
    $this->load_view (array (
        'message' => $message,
        'tag' => $tag
      ));
  }

  public function update ($id) {
    // 確認該 id 資料是否存在
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // 承接 POST 參數
    $name = trim ($this->input_post ('name'));

    // 檢查參數 使否格式正確
    if (!$name) {
      // 設定 session flash data，寫入失敗原因，然後導頁
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    // 設定 updata 欄位資料
    $tag->name = $name;

    // 儲存
    if ($tag->save ()) {
      // 設定 session flash data，寫入成功訊息，然後導頁
      identity ()->set_session ('_flash_message', '修改成功!', true);
      return redirect (array ($this->get_class (), 'index'), 'refresh');
    } else {
      // 設定 session flash data，寫入失敗原因，然後導頁
      identity ()->set_session ('_flash_message', '修改失敗!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }
  }

  public function destroy ($id) {
    // 確認該 id 資料是否存在
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // 刪除資料，並且設定 session flash data，寫入訊息，然後導頁
    if ($tag->destroy ())
      identity ()->set_session ('_flash_message', '刪除成功!', true);
    else
      identity ()->set_session ('_flash_message', '刪除失敗!', true);

    return redirect (array ($this->get_class (), 'index'), 'refresh');
  }
}
