<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class ArticleSource extends OaModel {

  static $table_name = 'article_sources';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function destroy () {
    return $this->delete ();
  }
  public function mini_href ($length = 80) {
    if (!isset ($this->href)) return '';
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->href), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->href);
  }
}