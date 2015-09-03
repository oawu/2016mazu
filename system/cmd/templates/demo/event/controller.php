{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Events extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    // 取出所有的 event
    $events = Event::all (array ('include' => array ('attendees')));

    // 取出 session flash data
    $message = identity ()->get_session ('_flash_message', true);

    // load view
    $this->load_view (array (
        'events' => $events,
        'message' => $message
      ));
  }

  public function show ($id) {
    // 確認該 id 資料是否存在
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // load view
    $this->load_view (array (
        'event' => $event
      ));
  }

  public function add () {
    // 取出 session flash data
    $message = identity ()->get_session ('_flash_message', true);

    // 引用 js lib
    // load view
    $this->add_js (base_url ('resource', 'javascript', 'underscore_v1.7.0', 'underscore-min.js'), false)
         ->load_view (array (
            'message' => $message
          ));
  }

  public function create () {
    // 承接 POST 參數
    $title     = trim ($this->input_post ('title'));
    $info      = trim ($this->input_post ('info'));
    $tag_ids   = $this->input_post ('tag_ids');
    $attendees = $this->input_post ('attendees');
    $cover     = $this->input_post ('cover', true, true);

    // 檢查參數 使否格式正確
    if (!($title && $info && $cover)) {
      // 設定 session flash data，寫入失敗原因，然後導頁
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    // 設定 create 用的資料
    $params = array (
        'title' => $title,
        'info' => $info,
        'cover' => ''
      );

    // 新增一筆資料
    if (verifyCreateOrm ($event = Event::create ($params))) {

      // 若新增成功的話順便 put 圖片
      if (!$event->cover->put ($cover)) {
        // put 圖片失敗則將該筆資料刪除
        $event->destroy ();

        // 設定 session flash data，寫入失敗原因，然後導頁
        identity ()->set_session ('_flash_message', '上傳圖片失敗!', true);
        return redirect (array ($this->get_class (), 'add'), 'refresh');
      }
      
      // 新增成功後，新增對應的 tag
      // 藉由 tag 的 ids 藉由 where in 塞選存在的 tag，以防止不存在的 tag id
      if ($tag_ids)
        array_map (function ($tag) use ($event) {
          return verifyCreateOrm (TagEventMap::create (array ('tag_id' => $tag->id, 'event_id' => $event->id)));
        }, Tag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $tag_ids))));

      // 參與者，先使用 array_unique 過濾掉重複名稱，然後新增參與者
      if ($attendees)
        array_map (function ($attendee) use ($event) {
          return verifyCreateOrm (Attendee::create (array ('event_id' => $event->id, 'name' => trim ($attendee))));
        }, array_unique ($attendees));

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
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // 取出 session flash data
    $message = identity ()->get_session ('_flash_message', true);

    // 引用 js lib
    // load view
    $this->add_js (base_url ('resource', 'javascript', 'underscore_v1.7.0', 'underscore-min.js'), false)
         ->load_view (array (
            'message' => $message,
            'event' => $event
          ));
  }

  public function update ($id) {
    // 確認該 id 資料是否存在
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // 承接 POST 參數
    $title = trim ($this->input_post ('title'));
    $info  = trim ($this->input_post ('info'));
    $tag_ids = ($tag_ids = $this->input_post ('tag_ids')) ? $tag_ids : array ();
    $old_attendees = ($old_attendees = $this->input_post ('old_attendees')) ? $old_attendees : array ();
    $cover = $this->input_post ('cover', true, true);
    $attendees = $this->input_post ('attendees');

    // 檢查參數 使否格式正確
    if (!($title && $info)) {
      // 設定 session flash data，寫入失敗原因，然後導頁
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    // 設定 updata 欄位資料
    $event->title = $title;
    $event->info = $info;

    // 刪除 取消 tag 的資料
    // 藉由 array_diff 將原本資料的 tag ids 與 POST 的 tag ids，取其差異
    // 其差異就是需要刪除的 ids
    $old_tag_ids = column_array ($event->tag_event_maps, 'tag_id');
    if ($delete_tag_ids = array_diff ($old_tag_ids, $tag_ids))
      TagEventMap::delete_all (array ('conditions' => array ('tag_id IN (?)', $delete_tag_ids)));

    // 新增 tag 的資料
    // 藉由 array_diff 將 POST 的 tag ids 與原本資料的 tag ids ，取其差異
    // 其差異就是需要新增的 ids
    // 一樣需要藉由 where in 塞選存在的 tag，以防止不存在的 tag id
    if ($create_tag_ids = array_diff ($tag_ids, $old_tag_ids))
      array_map (function ($tag) use ($event) {
        return verifyCreateOrm (TagEventMap::create (array ('tag_id' => $tag->id, 'event_id' => $event->id)));
      }, Tag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $create_tag_ids))));

    // 刪除 未參與的 attendee 的資料
    // 藉由 array_diff 將原本資料的 attendee ids 與 POST 的 attendee ids，取其差異
    // 其差異就是需要刪除的 ids
    if ($delete_attendee_ids = array_diff (column_array ($event->attendees, 'id'), column_array ($old_attendees, 'id')))
      Attendee::delete_all (array ('conditions' => array ('id IN (?)', $delete_attendee_ids)));

    // 更新參與者的資訊
    if ($old_attendees)
      array_map (function ($old_attendee) {
        Attendee::table ()->update ($set = array ('name' => trim ($old_attendee['name'])), array ('id' => $old_attendee['id']));
      }, $old_attendees);

    // 新增 attendee 的資料
    // 參與者，先使用 array_unique 過濾掉重複名稱，然後新增參與者
    if ($attendees)
      array_map (function ($attendee) use ($event) {
        return verifyCreateOrm (Attendee::create (array ('event_id' => $event->id, 'name' => trim ($attendee))));
      }, array_unique ($attendees));

    // 儲存，更新圖檔，如果沒有上傳圖片，則不更新
    if ($event->save () && (!$cover || $event->cover->put ($cover))) {
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
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    // 刪除資料，並且設定 session flash data，寫入訊息，然後導頁
    if ($event->destroy ())
      identity ()->set_session ('_flash_message', '刪除成功!', true);
    else
      identity ()->set_session ('_flash_message', '刪除失敗!', true);

    return redirect (array ($this->get_class (), 'index'), 'refresh');
  }
}
