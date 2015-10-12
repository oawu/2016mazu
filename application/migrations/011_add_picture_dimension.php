<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_picture_dimension extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `pictures` ADD `width` smallint(11) unsigned NOT NULL DEFAULT '0' COMMENT '原始寬度' AFTER `color_b`;"
    );
    $this->db->query (
      "ALTER TABLE `pictures` ADD `height` smallint(11) unsigned NOT NULL DEFAULT '0' COMMENT '原始高度' AFTER `width`;"
    );
  }
  public function down () {
    $this->db->query (
      "ALTER TABLE `pictures` DROP COLUMN `height`;"
    );
    $this->db->query (
      "ALTER TABLE `pictures` DROP COLUMN `width`;"
    );
  }
}