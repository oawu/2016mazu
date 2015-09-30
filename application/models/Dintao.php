<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintao extends OaModel {

  static $table_name = 'dintaos';

  static $has_one = array (
  );

  static $has_many = array (
    array ('sources', 'class_name' => 'DintaoSource', 'order' => 'sort ASC')
  );

  static $belongs_to = array (
  );

  const TYPE_OFFICIAL = 1;
  const TYPE_LOCAL    = 2;
  const TYPE_OTHER    = 3;
  static $types = array (
      self::TYPE_OFFICIAL => '駕前陣頭',
      self::TYPE_LOCAL => '地方陣頭',
      self::TYPE_OTHER => '其他介紹',
    );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('cover', 'DintaoCoverImageUploader');
  }
  public function destroy () {
    return self::transaction (function () {
      if ($this->sources)
        foreach ($this->sources as $source)
          if (!$source->destroy (false))
            return false;

      return $this->delete ();
    });
  }
  public function mini_content ($length = 100) {
    return mb_strimwidth (remove_ckedit_tag ($this->content), 0, $length, '…','UTF-8');
  }
  public function mini_keywords ($length = 50) {
    return mb_strimwidth ($this->keywords, 0, $length, '…','UTF-8');
  }
}