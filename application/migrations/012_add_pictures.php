<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_pictures extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `pictures` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL COMMENT 'User ID',
        `destroy_user_id` int(11) unsigned DEFAULT NULL COMMENT '刪除此筆的 User ID',
        
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `keywords` text COMMENT 'SEO 關鍵字',
        `content` text COMMENT '內容',
        `pv` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Page View',

        `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
        `name_color_r` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Red',
        `name_color_g` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Green',
        `name_color_b` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Blue',
        `name_width` smallint(11) unsigned NOT NULL DEFAULT 0 COMMENT '原始寬度',
        `name_height` smallint(11) unsigned NOT NULL DEFAULT 0 COMMENT '原始高度',

        `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '上下架，1 上架，0 下架',
        `is_compressor` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '是否壓縮過，1 是，0 否',
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
      "DROP TABLE `pictures`;"
    );
  }
}