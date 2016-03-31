<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class MarchMessage extends OaModel {

  static $table_name = 'march_messages';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public static function mail ($messages) {
    return Mail::send ('Message 錯誤！', array (
        'OA <comdan66@gmail.com>',
      ), $messages);
  }
  public static function put () {
    $black_list_ips = column_array (MarchMessageBlacklist::find ('all', array ('select' => 'ip')), 'ip');
    $path = FCPATH . 'temp/march_messages.json';
    $s3_path = 'api/march/messages.json';

    if (file_exists ($path)) return self::mail (array (
        '錯誤問題' => '放置 Message 到 s3 錯誤。',
        '錯誤原因' => '上一次的 .json 檔案尚未刪除！',
        '刪除鏈結' => base_url ('api', 'clean', 'messages'),
      ));

    if (!write_file ($path, json_encode (array ()))) return self::mail (array (
        '錯誤問題' => '放置 Message 到 s3 錯誤。',
        '錯誤原因' => '第一次寫入 .json 失敗！',
      ));

    $msgs = array_map (function ($msg) {
      return array (
          'a' => $msg->user_id ? true : false,
          'd' => $msg->id,
          'i' => $msg->ip,
          'm' => $msg->message,
          't' => $msg->created_at->format ('Y-m-d H:i:s')
        );
    }, MarchMessage::find ('all', array (
        'select' => 'id, ip, user_id, message, created_at',
        'limit' => 40,
        'order' => 'id DESC',
        'conditions' => $black_list_ips ? array ('ip NOT IN (?)', $black_list_ips) : array ()
      )));

    $unit = 10; //min
    $q = 0;
    $end = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * $q) . ' minutes'));
    $start = date ('Y-m-d H:i:s', strtotime (date ('Y-m-d H:i:s') . ' - ' . ($unit * ($q + 1)) . ' minutes'));

    $c = count (MarchMessage::find ('all', array ('group' => 'ip', 'conditions' => array ('created_at BETWEEN ? AND ?', $start, $end))));

    if (!write_file ($path, json_encode (array (
        's' => true,
        't' => date ('Y-m-d H:i:s'),
        'c' => $c * rand (5, 10),
        'm' => $msgs
      ))))
      return self::mail (array (
        '錯誤問題' => '放置 Message 到 s3 錯誤。',
        '錯誤原因' => '第二次寫入 .json 失敗！',
      ));

    if (!put_s3 ($path, $s3_path))
      return self::mail (array (
        '錯誤問題' => '放置 Message 到 s3 錯誤。',
        '錯誤原因' => '丟到 S3 失敗！',
      ));

    return @unlink ($path);
  }
}