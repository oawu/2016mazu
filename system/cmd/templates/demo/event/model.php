{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Event extends OaModel {

  static $table_name = 'events';

  // 建立 一對一 關聯變數
  static $has_one = array (
    array ('first_attendee', 'class_name' => 'Attendee', 'order' => 'id ASC'),
  );

  // 建立 一對多 關聯變數
  static $has_many = array (
    array ('tag_event_maps', 'class_name' => 'TagEventMap'),

    array ('attendees', 'class_name' => 'Attendee'),
    array ('tags', 'class_name' => 'Tag', 'through' => 'tag_event_maps')
  );

  // 建立 一對一 關聯變數
  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    // 綁定處理圖片的欄位
    OrmImageUploader::bind ('cover', 'EventCoverImageUploader');
  }

  // return bool
  public function destroy () {
    // 取出相對應的 tag_event_maps 資料，並且將其刪除
    if ($old_tag_ids = column_array ($this->tag_event_maps, 'tag_id'))
      TagEventMap::delete_all (array ('conditions' => array ('tag_id IN (?)', $old_tag_ids)));

    // 取出相對應的 attendees 資料，並且將其刪除
    if ($old_attendee_ids = column_array ($this->attendees, 'id'))
      Attendee::delete_all (array ('conditions' => array ('id IN (?)', $old_attendee_ids)));

    // 將圖片檔案刪除，然後將自身資料刪除，並且回傳成功或者失敗
    return $this->cover->cleanAllFiles () && $this->delete ();
  }
}