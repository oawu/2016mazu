<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_dintaos extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `dintaos` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL COMMENT 'User ID',
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `cover` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '封面',
        `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SEO 關鍵字',
        
        `content` text  COMMENT '內容',
        `type` tinyint(1) unsigned NOT NULL DEFAULT 3 COMMENT '1 聖前, 2 地方, 3 其他',
        `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排列順序，上至下 DESC',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `type_index` (`type`),
        KEY `user_id_index` (`user_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `dintaos`;"
    );
  }
}