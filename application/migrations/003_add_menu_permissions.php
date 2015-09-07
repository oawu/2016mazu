<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_menu_permissions extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `menu_permissions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `menu_id` int(11) NOT NULL COMMENT 'Menu ID',
        `role_id` int(11) NOT NULL COMMENT 'Role ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `menu_id_index` (`menu_id`),
        KEY `role_id_index` (`role_id`),
        UNIQUE KEY `menu_id_role_id_unique` (`menu_id`, `role_id`),
        FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `menu_permissions`;"
    );
  }
}