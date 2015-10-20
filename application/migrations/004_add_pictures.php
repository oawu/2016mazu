<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_pictures extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `pictures` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL COMMENT 'User ID',
        
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
        `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'SEO 關鍵字',
        `description` text  COMMENT '描述',
        `pv` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'Page View',

        `color_r` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Red',
        `color_g` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Green',
        `color_b` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'RGB Blue',
        `width` smallint(11) unsigned NOT NULL DEFAULT 0 COMMENT '原始寬度',
        `height` smallint(11) unsigned NOT NULL DEFAULT 0 COMMENT '原始高度',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `pictures`;"
    );
  }
}