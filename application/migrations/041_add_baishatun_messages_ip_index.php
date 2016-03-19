<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Migration_Add_baishatun_messages_ip_index extends CI_Migration {
  public function up () {
    $this->db->query (
      "ALTER TABLE `baishatun_messages` ADD INDEX `ip_index`(`ip`);"
    );
  }
  public function down () {
    $this->db->query (
      "DROP INDEX `ip_index` ON `baishatun_messages`;"
    );
  }
}