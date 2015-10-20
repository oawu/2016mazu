<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_youtube_tags extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `youtube_tags` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,

        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
        `cover` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '封面',
        `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SEO 關鍵字',
        `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排列順序，上至下 DESC',

        `cover_color_r` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Red',
        `cover_color_g` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Green',
        `cover_color_b` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Blue',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `name_index` (`name`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `youtube_tags`;"
    );
  }
}