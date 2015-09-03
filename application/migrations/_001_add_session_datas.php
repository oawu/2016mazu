<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_session_datas extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `session_datas` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `session_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'session ID',
        `ip_address` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'IP',
        `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'User Agent',
        `last_activity` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '上一次活動時間',
        `user_data` text NOT NULL COMMENT 'Session 內容',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `uid_index` (`session_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `session_datas`;"
    );
  }
}