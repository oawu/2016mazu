<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_gps_settings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `gps_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `is_crontab` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '是否執行 Crontab',
        `path_id` int(11) unsigned NOT NULL DEFAULT 1 COMMENT 'Path ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `gps_settings`;"
    );
  }
}