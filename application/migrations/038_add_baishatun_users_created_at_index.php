<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_baishatun_users_created_at_index extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `baishatun_users` ADD INDEX `created_at_index`(`created_at`);"
    );
  }
  public function down () {
    $this->db->query (
      "DROP INDEX `created_at_index` ON `baishatun_users`;"
    );
  }
}