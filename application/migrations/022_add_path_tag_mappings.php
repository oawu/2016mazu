<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_path_tag_mappings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `path_tag_mappings` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `path_id` int(11) unsigned NOT NULL COMMENT 'Youtube ID',
        `path_tag_id` int(11) unsigned NOT NULL COMMENT 'Youtube Tag ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `path_id_index` (`path_id`),
        KEY `path_tag_id_index` (`path_tag_id`),
        UNIQUE KEY `path_id_path_tag_id_unique` (`path_id`, `path_tag_id`),
        FOREIGN KEY (`path_id`) REFERENCES `paths` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`path_tag_id`) REFERENCES `path_tags` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `path_tag_mappings`;"
    );
  }
}