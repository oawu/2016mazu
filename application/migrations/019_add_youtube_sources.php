<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_youtube_sources extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `youtube_sources` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `youtube_id` int(11) unsigned NOT NULL COMMENT 'Youtube ID',
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        `href` text COMMENT '網址',
        `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排列順序，上至下 ASC',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `youtube_id_index` (`youtube_id`),
        FOREIGN KEY (`youtube_id`) REFERENCES `youtubes` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `youtube_sources`;"
    );
  }
}