<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_baishatun_users_ip extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `baishatun_users` ADD `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0' COMMENT 'IP' AFTER `id`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `baishatun_users` DROP COLUMN `ip`;"
    );
  }
}