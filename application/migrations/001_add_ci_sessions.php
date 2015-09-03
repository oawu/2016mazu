<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_ci_sessions extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `ci_sessions` (
        `session_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'session ID',
        `ip_address` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'IP',
        `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'User Agent',
        `last_activity` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '上一次活動時間',
        `user_data` text NOT NULL COMMENT 'Session 內容',
        PRIMARY KEY (`session_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `ci_sessions`;"
    );
  }
}