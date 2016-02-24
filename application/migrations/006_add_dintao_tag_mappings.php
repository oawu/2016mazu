<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_dintao_tag_mappings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `dintao_tag_mappings` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `dintao_id` int(11) unsigned NOT NULL COMMENT 'Dintao ID',
        `dintao_tag_id` int(11) unsigned NOT NULL COMMENT 'Dintao Tag ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `dintao_id_index` (`dintao_id`),
        KEY `dintao_tag_id_index` (`dintao_tag_id`),
        UNIQUE KEY `dintao_id_dintao_tag_id_unique` (`dintao_id`, `dintao_tag_id`),
        FOREIGN KEY (`dintao_id`) REFERENCES `dintaos` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`dintao_tag_id`) REFERENCES `dintao_tags` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `dintao_tag_mappings`;"
    );
  }
}