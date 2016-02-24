<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_user_roles extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `user_roles` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID(作者)',
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名稱',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `name_index` (`name`),
        KEY `user_id_index` (`user_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        UNIQUE KEY `name_user_id_unique` (`name`, `user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `user_roles`;"
    );
  }
}