<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_path_infos extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `path_infos` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `path_id` int(11) unsigned NOT NULL COMMENT 'Path ID',
        `user_id` int(11) unsigned NOT NULL COMMENT 'User ID',
        `destroy_user_id` int(11) unsigned DEFAULT NULL COMMENT '刪除此筆的 User ID',

        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `content` text  COMMENT '內容',
        `latitude` DOUBLE NOT NULL COMMENT '緯度',
        `longitude` DOUBLE NOT NULL COMMENT '經度',
        `type` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT 'Marker 樣式，1: 預設紅，2: 紫色，3: 黃色，4: 藍色，5: 灰色，6: 綠色',

        `cover` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '封面',
        `cover_color_r` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'Cover RGB Red',
        `cover_color_g` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'Cover RGB Green',
        `cover_color_b` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'Cover RGB Blue',
        `cover_width` smallint(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Cover 原始寬度',
        `cover_height` smallint(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Cover 原始高度',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `path_id_index` (`path_id`),
        KEY `user_id_index` (`user_id`),
        KEY `destroy_user_id_index` (`destroy_user_id`),
        KEY `destroy_user_id_path_id_index` (`destroy_user_id`, `path_id`),
        FOREIGN KEY (`path_id`) REFERENCES `paths` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`destroy_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `path_infos`;"
    );
  }
}