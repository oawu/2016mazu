<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_baishatun_messages_black_count extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `baishatun_messages` ADD `black_count` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '黑名單次數' AFTER `message`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `baishatun_messages` DROP COLUMN `black_count`;"
    );
  }
}