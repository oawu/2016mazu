<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Youtube extends OaModel {

  static $table_name = 'youtubes';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'YoutubeTagMapping', 'order' => 'sort DESC'),
    array ('tags', 'class_name' => 'YoutubeTag', 'through' => 'mappings'),
    array ('sources', 'class_name' => 'YoutubeSource', 'order' => 'sort ASC')
  );

  static $belongs_to = array (
  );

  private $youtube_image_urls = null;
  private $youtube_image_url = null;

  private $next = '';
  private $prev = '';

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('cover', 'YoutubeCoverImageUploader');
  }

  public function bigger_youtube_image_urls () {
    if ($this->youtube_image_url !== null)
      return $this->youtube_image_url;

    if (!($youtube_image_urls = $this->youtube_image_urls ()))
      return $this->youtube_image_url = '';

    usort ($youtube_image_urls, function ($a, $b) {
      return $a['width'] * $a['height'] < $b['width'] * $b['height'];
    });

    $image_url = array_shift ($youtube_image_urls);

    return $this->youtube_image_url = $image_url['url'];
  }
  public function youtube_image_urls () {
    if ($this->youtube_image_urls !== null)
      return $this->youtube_image_urls;

    $data = file_get_contents ('https://www.googleapis.com/youtube/v3/videos?id=' . $this->vid . '&key=' . Cfg::setting ('google', ENVIRONMENT, 'server_key') . '&part=snippet');
    $json = json_decode ($data, true);
    return $this->youtube_image_urls = array_filter (isset ($json['items'][0]['snippet']['thumbnails']) ? $json['items'][0]['snippet']['thumbnails'] : array (), function ($image) {
      return isset ($image['url']) && isset ($image['width']) && isset ($image['height']);
    });
  }

  public function mini_keywords ($length = 50) {
    return mb_strimwidth ($this->keywords, 0, $length, '…','UTF-8');
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
  public function next ($tag_name = '') {
    if ($this->next !== '') return $this->next;
    
    if (!$tag_name) {
      if (!($next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id <= ?', $this->id, $this->id)))))
        $next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ?', $this->id)));
    } else {
      if (!(($tag = YoutubeTag::find_by_name ($tag_name, array ('select' => 'id'))) && ($mapping = YoutubeTagMapping::find ('one', array ('conditions' => array ('youtube_id = ? AND youtube_tag_id = ?', $this->id, $tag->id))))))
        return $this->next = null;

      if (!($next = YoutubeTagMapping::find ('one', array ('order' => 'sort DESC', 'conditions' => array ('youtube_id != ? AND sort <= ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->sort, $mapping->youtube_tag_id)))))
        $next = YoutubeTagMapping::find ('one', array ('order' => 'sort DESC', 'conditions' => array ('youtube_id != ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_tag_id)));
      
      if (!($next && ($next = Youtube::find ('one', array ('conditions' => array ('id = ?', $next->youtube_id))))))
        return $this->next = null;
    }

    return $this->next = $next;
  }
  public function prev ($tag_name = '') {
    if ($this->prev !== '') return $this->prev;

    if (!$tag_name) {
      if (!($prev = Youtube::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id >= ?', $this->id, $this->id)))))
        $prev = Youtube::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ?', $this->id)));
    } else {
      if (!(($tag = YoutubeTag::find_by_name ($tag_name, array ('select' => 'id'))) && ($mapping = YoutubeTagMapping::find ('one', array ('conditions' => array ('youtube_id = ? AND youtube_tag_id = ?', $this->id, $tag->id))))))
        return $this->prev = null;

      if (!($prev = YoutubeTagMapping::find ('one', array ('order' => 'sort ASC', 'conditions' => array ('youtube_id != ? AND sort >= ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->sort, $mapping->youtube_tag_id)))))
        $prev = YoutubeTagMapping::find ('one', array ('order' => 'sort ASC', 'conditions' => array ('youtube_id != ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_tag_id)));

      if (!($prev && ($prev = Youtube::find ('one', array ('conditions' => array ('id = ?', $prev->youtube_id))))))
        return $this->prev = null;
    }

    return $this->prev = $prev;
  }
}