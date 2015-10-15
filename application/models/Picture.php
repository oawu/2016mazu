<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Picture extends OaModel {

  static $table_name = 'pictures';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'PictureTagMapping', 'order' => 'sort DESC'),
    array ('tags', 'class_name' => 'PictureTag', 'through' => 'mappings'),
    array ('sources', 'class_name' => 'PictureSource', 'order' => 'sort ASC')
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('name', 'PictureNameImageUploader');
  }
  public function mini_keywords ($length = 50) {
    return mb_strimwidth ($this->keywords, 0, $length, '…','UTF-8');
  }
  public function color ($type = 'rgba', $alpha = 1) {
    if (!(isset ($this->color_r) && isset ($this->color_r) && isset ($this->color_g)))
      return '';

    $alpha = $alpha <= 1 ? $alpha >= 0 ? $alpha : 0 : 1;

    switch ($type) {
      default:
      case 'rgba':
        return 'rgba(' . $this->color_r . ', ' . $this->color_r . ', ' . $this->color_g . ', ' . $alpha . ')';
        break;
      case 'rgb':
        return 'rgb(' . $this->color_r . ', ' . $this->color_r . ', ' . $this->color_g . ')';
        break;
      case 'hex':
        return '#' . color_hex ($this->color_r) . '' . color_hex ($this->color_r) . '' . color_hex ($this->color_g);
        break;
    }
  }
  public function update_color_dimension ($image_utility = null) {
    if (!(isset ($this->id) && isset ($this->name) && isset ($this->width) && isset ($this->height)))
      return false;

    if (!(isset ($this->id) && isset ($this->name) && isset ($this->color_r) && isset ($this->color_g) && isset ($this->color_b)))
      return false;

    if (!$image_utility)
      switch (Cfg::system ('orm_uploader', 'uploader', 'driver')) {
        case 'local':
          if (!file_exists ($fileName = FCPATH . implode ('/', $this->name->path ())))
            return false;

          $image_utility = ImageUtility::create ($fileName);
          break;

        case 's3':
          if (!(@S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $this->name->path ()), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$this->name)))) && file_exists ($fileName = FCPATH . implode ('/', $fileName))))
            return false;
          $image_utility = ImageUtility::create ($fileName);
          break;

        default:
          return false;
          break;
      }

    $return = true;
    $return &= $this->update_dimension ($image_utility);
    $return &= $this->update_color ($image_utility);

    return $return;
  }
  public function update_dimension ($image_utility = null) {
    if (!(isset ($this->id) && isset ($this->name) && isset ($this->width) && isset ($this->height)))
      return false;

    if (!$image_utility)
      switch (Cfg::system ('orm_uploader', 'uploader', 'driver')) {
        case 'local':
          if (!file_exists ($fileName = FCPATH . implode ('/', $this->name->path ())))
            return false;

          $image_utility = ImageUtility::create ($fileName);
          break;

        case 's3':
          if (!(@S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $this->name->path ()), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$this->name)))) && file_exists ($fileName = FCPATH . implode ('/', $fileName))))
            return false;
          $image_utility = ImageUtility::create ($fileName);
          break;

        default:
          return false;
          break;
      }
    if (!ImageUtility::verifyDimension ($dimension = $image_utility->getDimension ()))
      return false;

    $this->width = $dimension['width'];
    $this->height = $dimension['height'];

    if (in_array (Cfg::system ('orm_uploader', 'uploader', 'driver'), array ('s3')))
      @unlink ($fileName);

    return $this->save ();
  }
  public function update_color ($image_utility = null) {
    if (!(isset ($this->id) && isset ($this->name) && isset ($this->color_r) && isset ($this->color_g) && isset ($this->color_b)))
      return false;

    if (!$image_utility)
      switch (Cfg::system ('orm_uploader', 'uploader', 'driver')) {
        case 'local':
          if (!file_exists ($fileName = FCPATH . implode ('/', $this->name->path ())))
            return false;

          $image_utility = ImageUtility::create ($fileName);
          break;

        case 's3':
          if (!(@S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $this->name->path ()), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$this->name)))) && file_exists ($fileName = FCPATH . implode ('/', $fileName))))
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
    
    $this->color_r = max (0, min ($red, 255));
    $this->color_g = max (0, min ($green, 255));
    $this->color_b = max (0, min ($blue, 255));

    if (in_array (Cfg::system ('orm_uploader', 'uploader', 'driver'), array ('s3')))
      @unlink ($fileName);

    return $this->save ();
  }
  public function mini_description ($length = 100) {
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->description), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->description);
  }
  public function keywords () {
    return preg_split ("/\s+/", $this->keywords);
  }
  public function destroy () {
    if ($this->mappings)
      foreach ($this->mappings as $mapping)
        if (!$mapping->destroy ())
          return false;
    
    if ($this->sources)
      foreach ($this->sources as $source)
        if (!$source->destroy ())
          return false;

    return $this->name->cleanAllFiles () && $this->delete ();
  }
}