<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_store_tag_mappings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `store_tag_mappings` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `store_id` int(11) unsigned NOT NULL COMMENT 'Youtube ID',
        `store_tag_id` int(11) unsigned NOT NULL COMMENT 'Youtube Tag ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `store_id_index` (`store_id`),
        KEY `store_tag_id_index` (`store_tag_id`),
        UNIQUE KEY `store_id_store_tag_id_unique` (`store_id`, `store_tag_id`),
        FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`store_tag_id`) REFERENCES `store_tags` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `store_tag_mappings`;"
    );
  }
}