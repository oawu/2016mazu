<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_march_infos extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `march_infos` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `march_id` int(11) unsigned NOT NULL COMMENT 'March ID',
        `latitude` DOUBLE NOT NULL COMMENT '緯度',
        `longitude` DOUBLE NOT NULL COMMENT '經度',
        `msgs` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '訊息s',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `march_id_index` (`march_id`),
        FOREIGN KEY (`march_id`) REFERENCES `marches` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `march_infos`;"
    );
  }
}