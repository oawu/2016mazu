<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_roles extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `roles` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
        `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        PRIMARY KEY (`id`),
        KEY `name_index` (`name`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );

    $this->db->query (
      "INSERT INTO `roles` (`name`, `description`)
        VALUES ('root', '最終權限'),
               ('admin', '後台管理員'),
               ('login', '一般會員'),
               ('black', '黑名單會員'),
               ('guest', '一般遊客');"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `roles`;"
    );
  }
}