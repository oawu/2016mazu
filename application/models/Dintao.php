<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Dintao extends OaModel {

  static $table_name = 'dintaos';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'DintaoTagMapping', 'order' => 'dintao_id DESC'),
    array ('tags', 'class_name' => 'DintaoTag', 'through' => 'mappings'),
    array ('sources', 'class_name' => 'DintaoSource', 'order' => 'sort ASC')
  );

  static $belongs_to = array (
    array ('user', 'class_name' => 'User')
  );

  private $next = array ();
  private $prev = array ();
  private $also = array ();

  const NO_ENABLED = 0;
  const IS_ENABLED = 1;

  static $isIsEnabledNames = array(
    self::NO_ENABLED => '關閉',
    self::IS_ENABLED => '啟用',
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('cover', 'DintaoCoverImageUploader');
  }

  public function mini_keywords ($length = 50) {
    return mb_strimwidth ($this->keywords, 0, $length, '…','UTF-8');
  }
  public function mini_title ($length = 25) {
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->title), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->title);
  }
  public function mini_content ($length = 100) {
    return $length ? mb_strimwidth (remove_ckedit_tag ($this->content), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->content);
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

    return $this->cover->cleanAllFiles () && $this->delete ();
  }
  public function next ($tag_id = 0) {
    $tag_id = is_object ($tag_id) ? $tag_id->id : $tag_id;
    if (isset ($this->next[$tag_id])) return $this->next[$tag_id];

    $is_loop = false;
    if (($tag_id && ($tag = DintaoTag::find_by_id ($tag_id, array ('select' => 'id'))) && ($mapping = DintaoTagMapping::find ('one', array ('conditions' => array ('dintao_id = ? AND dintao_tag_id = ?', $this->id, $tag->id)))) && (!(($dintao_ids = column_array (DintaoTagMapping::find ('all', array ('select' => 'dintao_id', 'order' => 'dintao_id DESC', 'conditions' => array ('dintao_id != ? AND dintao_id <= ? AND dintao_tag_id = ?', $mapping->dintao_id, $mapping->dintao_id, $mapping->dintao_tag_id))), 'dintao_id')) && ($next = Dintao::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $dintao_ids, self::IS_ENABLED)))))) && (!($is_loop && ($dintao_ids = column_array (DintaoTagMapping::find ('all', array ('select' => 'dintao_id', 'conditions' => array ('dintao_id != ? AND dintao_tag_id = ?', $mapping->dintao_id, $mapping->dintao_tag_id))), 'dintao_id')) && ($next = Dintao::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $dintao_ids, self::IS_ENABLED))))))) || (!($tag_id || ($next = Dintao::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id <= ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $this->id, self::IS_ENABLED)))) || ($is_loop && ($next = Dintao::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, self::IS_ENABLED))))))))
      $next = null;

    return $this->next[$tag_id] = $next;
  }
  public function prev ($tag_id = 0) {
    $tag_id = is_object ($tag_id) ? $tag_id->id : $tag_id;
    if (isset ($this->prev[$tag_id])) return $this->prev[$tag_id];

    $is_loop = false;
    if (($tag_id && ($tag = DintaoTag::find_by_id ($tag_id, array ('select' => 'id'))) && ($mapping = DintaoTagMapping::find ('one', array ('conditions' => array ('dintao_id = ? AND dintao_tag_id = ?', $this->id, $tag->id)))) && (!(($dintao_ids = column_array (DintaoTagMapping::find ('all', array ('select' => 'dintao_id', 'order' => 'dintao_id ASC', 'conditions' => array ('dintao_id != ? AND dintao_id >= ? AND dintao_tag_id = ?', $mapping->dintao_id, $mapping->dintao_id, $mapping->dintao_tag_id))), 'dintao_id')) && ($prev = Dintao::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $dintao_ids, self::IS_ENABLED)))))) && (!($is_loop && ($dintao_ids = column_array (DintaoTagMapping::find ('all', array ('select' => 'dintao_id', 'conditions' => array ('dintao_id != ? AND dintao_tag_id = ?', $mapping->dintao_id, $mapping->dintao_tag_id))), 'dintao_id')) && ($prev = Dintao::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $dintao_ids, self::IS_ENABLED))))))) || (!($tag_id || ($prev = Dintao::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id >= ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $this->id, self::IS_ENABLED)))) || ($is_loop && ($prev = Dintao::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, self::IS_ENABLED))))))))
      $prev = null;

    return $this->prev[$tag_id] = $prev;
  }
  public function also ($tag_id = 0, $limit = 5) {
    $tag_id = is_object ($tag_id) ? $tag_id->id : $tag_id;
    if (isset ($this->also[$tag_id])) return $this->also[$tag_id];

    if (!($tag_id && ($tag = DintaoTag::find_by_id ($tag_id, array ('select' => 'id'))) && ($dintao_ids = column_array (DintaoTagMapping::find ('all', array ('select' => 'dintao_id', 'limit' => $limit + 1, 'order' => 'dintao_id DESC', 'conditions' => array ('dintao_tag_id = ?', $tag->id))), 'dintao_id')) && ($also = Dintao::find ('all', array ('order' => 'id DESC', 'limit' => $limit, 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $dintao_ids, self::IS_ENABLED))))) && !($tag_id || ($also = Dintao::find ('all', array ('order' => 'id DESC', 'limit' => $limit, 'conditions' => array ('id != ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, self::IS_ENABLED))))))
      $also = array ();

    return $this->also[$tag_id] = $also;
  }
  public function content_page_last_uri () {
    return $this->id . '-' . oa_url_encode ($this->title);
  }
  public function content_page_url ($tag = null) {
    return $tag ? base_url ('tag', is_numeric ($tag) ? $tag : $tag->id, 'dintao', $this->content_page_last_uri ()) : base_url ('dintao', $this->content_page_last_uri ());
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

  public function update_cover_color_and_dimension ($image_utility = null) {
    if (!(isset ($this->id) && isset ($this->cover) && isset ($this->cover_width) && isset ($this->cover_height)))
      return false;

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

    $return = true;
    $return &= $this->update_dimension ($image_utility);
    $return &= $this->update_cover_color ($image_utility);

    return $return;
  }
  public function update_dimension ($image_utility = null) {
    if (!(isset ($this->id) && isset ($this->cover) && isset ($this->cover_width) && isset ($this->cover_height)))
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
    if (!ImageUtility::verifyDimension ($dimension = $image_utility->getDimension ()))
      return false;

    $this->cover_width = $dimension['width'];
    $this->cover_height = $dimension['height'];

    if (in_array (Cfg::system ('orm_uploader', 'uploader', 'driver'), array ('s3')))
      @unlink ($fileName);

    return $this->save ();
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

    $red = round ($analysis_datas['r'] / 10) * 10; $green = round ($analysis_datas['g'] / 10) * 10; $blue = round ($analysis_datas['b'] / 10) * 10;
    $red += (round (($red - $average) / 10) * 1.125) * 10; $green += (round (($green - $average) / 10) * 1.125) * 10; $blue += (round (($blue - $average) / 10) * 1.125) * 10;
    $red = round ($red > 0 ? $red < 256 ? $red : 255 : 0); $green = round ($green > 0 ? $green < 256 ? $green : 255 : 0); $blue = round ($blue > 0 ? $blue < 256 ? $blue : 255 : 0);
    $this->cover_color_r = max (0, min ($red, 255)); $this->cover_color_g = max (0, min ($green, 255)); $this->cover_color_b = max (0, min ($blue, 255));

    if (in_array (Cfg::system ('orm_uploader', 'uploader', 'driver'), array ('s3')))
      @unlink ($fileName);

    return $this->save ();
  }
}