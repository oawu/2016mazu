<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_users extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `users` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uid` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Facebook UID',
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名稱',
        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '電子郵件',
        `facebook_url` text NOT NULL COMMENT '臉書頁面網址',
        `login_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登入次數',
        `logined_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '上次登入時間',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `uid_index` (`uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `users`;"
    );
  }
}