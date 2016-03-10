<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_picture_tag_mappings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `picture_tag_mappings` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `picture_id` int(11) unsigned NOT NULL COMMENT 'Picture ID',
        `picture_tag_id` int(11) unsigned NOT NULL COMMENT 'Picture Tag ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `picture_id_index` (`picture_id`),
        KEY `picture_tag_id_index` (`picture_tag_id`),
        UNIQUE KEY `picture_id_picture_tag_id_unique` (`picture_id`, `picture_tag_id`),
        FOREIGN KEY (`picture_id`) REFERENCES `pictures` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`picture_tag_id`) REFERENCES `picture_tags` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `picture_tag_mappings`;"
    );
  }
}