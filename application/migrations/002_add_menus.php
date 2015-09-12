<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_menus extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `menus` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `menu_id` int(11) DEFAULT NULL,

        `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文字',
        `href` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '網址',
        `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '圖示',

        `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '類別',
        `method` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '方法',
        `target` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '鏈結開啟方法',
        `sort` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '排列順序，上至下 ASC',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        PRIMARY KEY (`id`),
        KEY `menu_id_index` (`menu_id`),
        FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `menus`;"
    );
  }
}