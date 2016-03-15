<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_baishatun_com_paths extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `baishatun_com_paths` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `lat` DOUBLE NOT NULL COMMENT '原始緯度',
        `lng` DOUBLE NOT NULL COMMENT '原始經度',
        `lat2` DOUBLE NOT NULL COMMENT '校正緯度',
        `lng2` DOUBLE NOT NULL COMMENT '校正經度',
        `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '住址',
        `target` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'target',
        `distance` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'distance',
        `time_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '時間',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `baishatun_com_paths`;"
    );
  }
}