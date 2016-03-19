<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_baishatun_messages_user_id extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `baishatun_messages` ADD `user_id` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'User ID(作者)' AFTER `id`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `baishatun_messages` DROP COLUMN `user_id`;"
    );
  }
}