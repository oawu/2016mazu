<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class GpsSetting extends OaModel {

  static $table_name = 'gps_settings';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  const NO_CRONTAB = 0;
  const IS_CRONTAB = 1;

  static $isIsCrontabNames = array(
    self::NO_CRONTAB => '關閉',
    self::IS_CRONTAB => '啟用',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
}