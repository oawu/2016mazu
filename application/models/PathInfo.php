<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class PathInfo extends OaModel {

  static $table_name = 'path_infos';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  const TYPE_RED    = 1;
  const TYPE_PURPLE = 2;
  const TYPE_YELLOW = 3;
  const TYPE_BLUE   = 4;
  const TYPE_GRAY   = 5;
  const TYPE_GREEN  = 6;

  public static $type_names = array (
      self::TYPE_RED    => '紅色',
      self::TYPE_PURPLE => '紫色',
      self::TYPE_YELLOW => '黃色',
      self::TYPE_BLUE   => '藍色',
      self::TYPE_GRAY   => '灰色',
      self::TYPE_GREEN  => '綠色',
    );
  public static $type_en_names = array (
      self::TYPE_RED    => 'red',
      self::TYPE_PURPLE => 'purple',
      self::TYPE_YELLOW => 'yellow',
      self::TYPE_BLUE   => 'blue',
      self::TYPE_GRAY   => 'gray',
      self::TYPE_GREEN  => 'green',
    );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('image', 'PathInfoImageImageUploader');
    OrmImageUploader::bind ('cover', 'PathInfoCoverImageUploader');
  }

  public function destroy () {
    return $this->image->cleanAllFiles () && $this->cover->cleanAllFiles () && $this->delete ();
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
        return '#' . cover_color_hex ($this->cover_color_r) . '' . cover_color_hex ($this->cover_color_r) . '' . cover_color_hex ($this->cover_color_g);
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

    if (!(($analysis_datas = $image_utility->resize (10, 10, 'w')->getAnalysisDatas (1)) && isset ($analysis_datas[0]['color']) && ($analysis_datas = $analysis_datas[0]['color']) && (isset ($analysis_datas['r']) && isset ($analysis_datas['g']) && isset ($analysis_datas['b']))))
      return false;

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
  
  public function mini_description ($length = 100) {
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->description), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->description);
  }
  
  public function put_image () {
    return $this->image->put_url ($this->picture ('100x100', 'server_key'));
  }

  public function picture ($size = '60x60', $type = 'client_key', $zoom = 13, $marker_size = 'normal') {
    $marker_size = in_array ($marker_size, array ('normal', 'tiny', 'mid', 'small')) ? $marker_size : 'normal';
    return "http://maps.googleapis.com/maps/api/staticmap?center=" . $this->latitude . "," . $this->longitude . "&zoom=" . $zoom . "&size=" . $size . "&markers=size:" . $marker_size . "|color:" . PathInfo::$type_en_names[$this->type] . "|" . $this->latitude . "," . $this->longitude . "&key=" . Cfg::setting ('google', ENVIRONMENT, $type);
  }

  public function icon_url () {
    return base_url ('resource', 'image', 'map', 'spotlight-' . $this->type . '.png');
  }
  public static function icon_urls () {
    $icon_urls = array ();
    foreach (self::$type_names as $key => $value)
      $icon_urls[$key] = base_url ('resource', 'image', 'map', 'spotlight-' . $key . '.png');
    return $icon_urls;
  }
}