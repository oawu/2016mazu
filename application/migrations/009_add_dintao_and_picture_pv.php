<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_dintao_and_picture_pv extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `dintaos` ADD `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Page View' AFTER `keywords`;"
    );
    $this->db->query (
      "ALTER TABLE `pictures` ADD `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Page View' AFTER `keywords`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `pictures` DROP COLUMN `pv`;"
    );
    $this->db->query (
      "ALTER TABLE `dintaos` DROP COLUMN `pv`;"
    );
  }
}