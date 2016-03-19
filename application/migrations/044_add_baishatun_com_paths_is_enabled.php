<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_baishatun_com_paths_is_enabled extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `baishatun_com_paths` ADD `is_enabled` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '上下架，1 上架，0 下架' AFTER `lng2`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `baishatun_com_paths` DROP COLUMN `is_enabled`;"
    );
  }
}