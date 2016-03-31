<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_article_tag_mappings extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `article_tag_mappings` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `article_id` int(11) unsigned NOT NULL COMMENT 'article ID',
        `article_tag_id` int(11) unsigned NOT NULL COMMENT 'article Tag ID',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `article_id_index` (`article_id`),
        KEY `article_tag_id_index` (`article_tag_id`),
        UNIQUE KEY `article_id_article_tag_id_unique` (`article_id`, `article_tag_id`),
        FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`article_tag_id`) REFERENCES `article_tags` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `article_tag_mappings`;"
    );
  }
}