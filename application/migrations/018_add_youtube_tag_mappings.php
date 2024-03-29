<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_youtube_tag_mappings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `youtube_tag_mappings` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `youtube_id` int(11) unsigned NOT NULL COMMENT 'Youtube ID',
        `youtube_tag_id` int(11) unsigned NOT NULL COMMENT 'Youtube Tag ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `youtube_id_index` (`youtube_id`),
        KEY `youtube_tag_id_index` (`youtube_tag_id`),
        UNIQUE KEY `youtube_id_youtube_tag_id_unique` (`youtube_id`, `youtube_tag_id`),
        FOREIGN KEY (`youtube_id`) REFERENCES `youtubes` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`youtube_tag_id`) REFERENCES `youtube_tags` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `youtube_tag_mappings`;"
    );
  }
}