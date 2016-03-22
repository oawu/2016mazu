<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_marches extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `marches` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '圖示',
        `is_finished` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否完成，1 完成，0 未完成',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `marches`;"
    );
  }
}