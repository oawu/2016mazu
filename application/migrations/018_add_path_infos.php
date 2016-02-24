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

        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `description` text  COMMENT '描述',
        `latitude` DOUBLE NOT NULL COMMENT '緯度',
        `longitude` DOUBLE NOT NULL COMMENT '經度',
        `type` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT 'Marker 樣式，1: 預設紅，2: 紫色，3: 黃色，4: 藍色，5: 灰色，6: 綠色',
        `image` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Map 靜態圖檔',

        `cover` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '封面',
        `cover_color_r` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Red',
        `cover_color_g` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Green',
        `cover_color_b` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Blue',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `path_id_index` (`path_id`),
        FOREIGN KEY (`path_id`) REFERENCES `paths` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `path_infos`;"
    );
  }
}