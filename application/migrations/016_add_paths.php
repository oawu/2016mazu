<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_paths extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `paths` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL COMMENT 'User ID',
        `destroy_user_id` int(11) unsigned DEFAULT NULL COMMENT '刪除此筆的 User ID',

        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `keywords` text  COMMENT 'SEO 關鍵字',
        `pv` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Page View',
        `length` DOUBLE NOT NULL DEFAULT 0 COMMENT '總長度(m)',
        `image` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Map 靜態圖檔',

        `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '上下架，1 上架，0 下架',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `user_id_index` (`user_id`),
        KEY `destroy_user_id_index` (`destroy_user_id`),
        KEY `is_enabled_index` (`is_enabled`),
        KEY `id_destroy_user_id_is_enabled_index` (`id`, `destroy_user_id`, `is_enabled`),
        KEY `destroy_user_id_is_enabled_index` (`destroy_user_id`, `is_enabled`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`destroy_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `paths`;"
    );
  }
}