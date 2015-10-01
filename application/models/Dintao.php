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
  private function _to_hex ($n) {
    if (!$n = intval ($n))
      return '00';

    $i1 = (int) ($n - ($n % 16)) / 16;
    $i2 = (int) $n % 16;

    return substr ('0123456789ABCDEF', $i1, 1) . substr ('0123456789ABCDEF', $i2, 1);
  }
  public function cover_color ($type = 'rgba', $alpha = 1) {
    if (!(isset ($this->cover_color_r) && isset ($this->cover_color_r) && isset ($this->cover_color_g)))
      return '';

    $alpha = $alpha <= 1 ? $alpha >= 0 ? $alpha : 0 : 1;

    switch ($type) {
      default:
      case 'rgba':
        return 'rgba(' . $this->cover_color_r . ', ' . $this->cover_color_r . ', ' . $this->cover_color_g . ', ' . $alpha . ')';
        break;
      case 'rgb':
        return 'rgb(' . $this->cover_color_r . ', ' . $this->cover_color_r . ', ' . $this->cover_color_g . ')';
        break;
      case 'hex':
        return '#' . $this->_to_hex ($this->cover_color_r) . '' . $this->_to_hex ($this->cover_color_r) . '' . $this->_to_hex ($this->cover_color_g);
        break;
    }

  }
  public function update_cover_color ($image_utility = null) {
    if (!(isset ($this->id) && isset ($this->cover) && isset ($this->cover_color_r) && isset ($this->cover_color_g) && isset ($this->cover_color_b)))
      return false;

    if (!$image_utility)
      switch (Cfg::system ('orm_uploader', 'uploader', 'driver')) {
        case 'local':
          if (!file_exists ($fileName = FCPATH . implode ('/', $this->cover->path ())))
            return false;

          $image_utility = ImageUtility::create ($fileName);
          break;

        case 's3':
          if (!(@S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $this->cover->path ()), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$this->cover)))) && file_exists ($fileName = FCPATH . implode ('/', $fileName))))
            return false;
          $image_utility = ImageUtility::create ($fileName);
          break;

        default:
          return false;
          break;
      }

    if (($analysis_datas = $image_utility->resize (10, 10, 'w')->getAnalysisDatas (1)) && isset ($analysis_datas[0]['color']) && ($analysis_datas = $analysis_datas[0]['color']) && (isset ($analysis_datas['r']) && isset ($analysis_datas['g']) && isset ($analysis_datas['b']))) {
      $average = 128;

      $red = round ($analysis_datas['r'] / 10) * 10;
      $green = round ($analysis_datas['g'] / 10) * 10;
      $blue = round ($analysis_datas['b'] / 10) * 10;

      $red += (round (($red - $average) / 10) * 1.125) * 10;
      $green += (round (($green - $average) / 10) * 1.125) * 10;
      $blue += (round (($blue - $average) / 10) * 1.125) * 10;

      $red = round ($red > 0 ? $red < 256 ? $red : 255 : 0);
      $green = round ($green > 0 ? $green < 256 ? $green : 255 : 0);
      $blue = round ($blue > 0 ? $blue < 256 ? $blue : 255 : 0);
      
      $this->cover_color_r = max (0, min ($red, 255));
      $this->cover_color_g = max (0, min ($green, 255));
      $this->cover_color_b = max (0, min ($blue, 255));

      if (in_array (Cfg::system ('orm_uploader', 'uploader', 'driver'), array ('s3')))
        @unlink ($fileName);

      return $this->save ();
    }
  }
  public function destroy () {
    if ($this->sources)
      foreach ($this->sources as $source)
        if (!$source->destroy ())
          return false;

    return $this->delete ();
  }
  public function mini_content ($length = 100) {
    return mb_strimwidth (remove_ckedit_tag ($this->content), 0, $length, '…','UTF-8');
  }
  public function mini_keywords ($length = 50) {
    return mb_strimwidth ($this->keywords, 0, $length, '…','UTF-8');
  }
  public function keywords () {
    return preg_split ("/\s+/", $this->keywords);
  }
}