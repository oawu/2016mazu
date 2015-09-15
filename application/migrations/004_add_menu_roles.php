<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_menu_roles extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `menu_roles` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `menu_id` int(11) NOT NULL COMMENT 'Menu ID',
        `role` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `menu_id_index` (`menu_id`),
        KEY `role_index` (`role`),
        UNIQUE KEY `menu_id_role_unique` (`menu_id`, `role`),
        FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `menu_roles`;"
    );
  }
}