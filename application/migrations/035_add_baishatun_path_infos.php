<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_baishatun_path_infos extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `baishatun_path_infos` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `lat` DOUBLE NOT NULL COMMENT '緯度',
        `lng` DOUBLE NOT NULL COMMENT '經度',
        `msgs` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '訊息s',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `baishatun_path_infos`;"
    );
  }
}