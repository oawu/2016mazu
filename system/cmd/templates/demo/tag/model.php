{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Tag extends OaModel {

  static $table_name = 'tags';

  // 建立 一對一 關聯變數
  static $has_one = array (
  );

  // 建立 一對多 關聯變數
  static $has_many = array (
    array ('tag_event_maps', 'class_name' => 'TagEventMap'),

    array ('events', 'class_name' => 'Event', 'through' => 'tag_event_maps')
  );

  // 建立 一對一 關聯變數
  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  
  // return bool
  public function destroy () {
    // 將自身資料刪除，並且回傳成功或者失敗
    return $this->delete ();
  }
}