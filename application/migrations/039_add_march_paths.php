<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_march_paths extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `march_paths` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `march_id` int(11) unsigned NOT NULL COMMENT 'march ID',

        `sqlite_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'APP SQLite ID',
        `latitude` DOUBLE NOT NULL COMMENT '原始緯度',
        `longitude` DOUBLE NOT NULL COMMENT '原始經度',
        `altitude` DOUBLE NOT NULL DEFAULT -1 COMMENT '海拔(公尺)',
        `accuracy_horizontal` DOUBLE NOT NULL DEFAULT -1 COMMENT '水平準確度(公尺)',
        `accuracy_vertical` DOUBLE NOT NULL DEFAULT -1 COMMENT '垂直準確度(公尺)',
        `speed` DOUBLE NOT NULL DEFAULT -1 COMMENT '移動速度(公尺/秒)',

        `latitude2` DOUBLE NOT NULL COMMENT '校正緯度',
        `longitude2` DOUBLE NOT NULL COMMENT '校正經度',

        `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '是否採用，1 採用，0 不採用',
        `time_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '時間',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `march_id_is_enabled_index` (`march_id`, `is_enabled`),
        KEY `id_march_id_is_enabled_index` (`id`, `march_id`, `is_enabled`),
        KEY `march_id_is_enabled_accuracy_horizontal_index` (`march_id`, `is_enabled`, `accuracy_horizontal`),
        FOREIGN KEY (`march_id`) REFERENCES `marches` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `march_paths`;"
    );
  }
}