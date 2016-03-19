<?php defined('BASEPATH') OR exit('No direct scris_enabledt access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_baishatun_com_paths_id_is_enabled_index extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `baishatun_com_paths` ADD INDEX `id_is_enabled_index`(`id`, `is_enabled`);"
    );
  }
  public function down () {
    $this->db->query (
      "DROP INDEX `id_is_enabled_index` ON `baishatun_com_paths`;"
    );
  }
}