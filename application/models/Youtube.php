<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Youtube extends OaModel {

  static $table_name = 'youtubes';

  static $has_one = array (
  );

  static $has_many = array (
    array ('mappings', 'class_name' => 'YoutubeTagMapping', 'order' => 'youtube_id DESC'),
    array ('tags', 'class_name' => 'YoutubeTag', 'through' => 'mappings'),
    array ('sources', 'class_name' => 'YoutubeSource', 'order' => 'sort ASC')
  );

  static $belongs_to = array (
    array ('user', 'class_name' => 'User')
  );

  private $youtube_image_urls = null;
  private $youtube_image_url = null;
  private $youtube_info = null;

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

    OrmImageUploader::bind ('cover', 'YoutubeCoverImageUploader');
  }

  public static function search_youtube ($options = array ()) {
    $CI  =& get_instance ();
    $CI->load->library ('Google');
    $client = new Google_Client ();
    $client->setDeveloperKey (Cfg::setting ('google', ENVIRONMENT, 'server_key'));
    $youtube = new Google_Service_YouTube ($client);

    try {
      return array_map (function ($item) {
        return Youtube::google_SearchResultSnippet_format ($item);
      }, $youtube->search->listSearch ('id, snippet', array_merge (array (
                      'type' => 'video'
                    ), $options))->items);
    } catch (Exception $e) {
      return array ();
    }
  }
  public static function google_SearchResultSnippet_format ($item) {
    $sizes = array ('getDefault', 'getHigh', 'getMaxres', 'getMedium', 'getStandard');
    $id = is_a ($item, 'Google_Service_YouTube_SearchResult') ? $item->id->videoId : (is_a ($item, 'Google_Service_YouTube_Video') ? $item->id : '');

    return $id && isset ($item->snippet) ? array (
          'id' => $id,
          'content' => isset ($item->snippet->content) ? $item->snippet->content : '',
          'title' => isset ($item->snippet->title) ? $item->snippet->title : '',
          'tags' => isset ($item->snippet->tags) ? $item->snippet->tags : array (),
          'publishedAt' => isset ($item->snippet->publishedAt) ? $item->snippet->publishedAt : '',
          'thumbnails' => isset ($item->snippet->thumbnails) ? array_filter (array_map (function ($size) use ($item) {
              if (!method_exists ($item->snippet->thumbnails, $size))
                return null;
      
              $thumbnail = call_user_func_array (array ($item->snippet->thumbnails, $size), array ());
      
              if (!isset ($thumbnail->url))
                return null;

              return array_merge (array ('url' => $thumbnail->url), isset ($thumbnail->width) && isset ($thumbnail->height) ? array (
                    'width' => $thumbnail->width,
                    'height' => $thumbnail->height
                  ) : array ());
            }, $sizes)) : array (),
        ) : array ();
  }

  public function bigger_youtube_image_urls () {
    if ($this->youtube_image_url !== null)
      return $this->youtube_image_url;

    if (!($youtube_image_urls = $this->youtube_image_urls ()))
      return $this->youtube_image_url = '';
    else
      $this->youtube_image_url = $youtube_image_urls[0]['url'];

    if (!($youtube_image_urls = array_filter ($youtube_image_urls, function ($image) { return isset ($image['width']) && isset ($image['height']); })))
      return $this->youtube_image_url;

    usort ($youtube_image_urls, function ($a, $b) {
      return $a['width'] * $a['height'] < $b['width'] * $b['height'];
    });

    $image_url = array_shift ($youtube_image_urls);

    return $this->youtube_image_url = $image_url['url'];
  }
  public function youtube_image_urls () {
    if ($this->youtube_image_urls !== null)
      return $this->youtube_image_urls;

    $youtube_info = $this->youtube_info ();
    return $this->youtube_image_urls = isset ($youtube_info['thumbnails']) && ($thumbnails = $youtube_info['thumbnails']) ? $youtube_info['thumbnails'] : array ();
  }

  public function youtube_info () {
    if ($this->youtube_info !== null) return $this->youtube_info;

    $this->CI->load->library ('Google');
    $client = new Google_Client ();
    $client->setDeveloperKey (Cfg::setting ('google', ENVIRONMENT, 'server_key'));
    $youtube = new Google_Service_YouTube ($client);

    try {
      $searchResponse = $youtube->videos->listVideos ('id, snippet',
            array ('id' => $this->vid
          ));

      if (!isset ($searchResponse->items[0]))
        return $this->youtube_info = array ();
      
      return $this->youtube_info = Youtube::google_SearchResultSnippet_format ($searchResponse->items[0]);
    } catch (Exception $e) {
      return $this->youtube_info = array ();
    }
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

  public function content_page_last_uri () {
    return $this->id . '-' . oa_url_encode ($this->title);
  }
  public function content_page_url ($tag = null) {
    return $tag ? base_url ('tag', is_numeric ($tag) ? $tag : $tag->id, 'youtube', $this->content_page_last_uri ()) : base_url ('youtube', $this->content_page_last_uri ());
  }

  public function next ($tag_id = 0) {
    $tag_id = is_object ($tag_id) ? $tag_id->id : $tag_id;
    if (isset ($this->next[$tag_id])) return $this->next[$tag_id];

    $is_loop = false;
    if (($tag_id && ($tag = YoutubeTag::find_by_id ($tag_id, array ('select' => 'id'))) && ($mapping = YoutubeTagMapping::find ('one', array ('conditions' => array ('youtube_id = ? AND youtube_tag_id = ?', $this->id, $tag->id)))) && (!(($youtube_ids = column_array (YoutubeTagMapping::find ('all', array ('select' => 'youtube_id', 'order' => 'youtube_id DESC', 'conditions' => array ('youtube_id != ? AND youtube_id <= ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_id, $mapping->youtube_tag_id))), 'youtube_id')) && ($next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $youtube_ids, self::IS_ENABLED)))))) && (!($is_loop && ($youtube_ids = column_array (YoutubeTagMapping::find ('all', array ('select' => 'youtube_id', 'conditions' => array ('youtube_id != ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_tag_id))), 'youtube_id')) && ($next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $youtube_ids, self::IS_ENABLED))))))) || (!($tag_id || ($next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id <= ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $this->id, self::IS_ENABLED)))) || ($is_loop && ($next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, self::IS_ENABLED))))))))
      $next = null;

    return $this->next[$tag_id] = $next;
  }
  public function prev ($tag_id = 0) {
    $tag_id = is_object ($tag_id) ? $tag_id->id : $tag_id;
    if (isset ($this->prev[$tag_id])) return $this->prev[$tag_id];

    $is_loop = false;
    if (($tag_id && ($tag = YoutubeTag::find_by_id ($tag_id, array ('select' => 'id'))) && ($mapping = YoutubeTagMapping::find ('one', array ('conditions' => array ('youtube_id = ? AND youtube_tag_id = ?', $this->id, $tag->id)))) && (!(($youtube_ids = column_array (YoutubeTagMapping::find ('all', array ('select' => 'youtube_id', 'order' => 'youtube_id ASC', 'conditions' => array ('youtube_id != ? AND youtube_id >= ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_id, $mapping->youtube_tag_id))), 'youtube_id')) && ($prev = Youtube::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $youtube_ids, self::IS_ENABLED)))))) && (!($is_loop && ($youtube_ids = column_array (YoutubeTagMapping::find ('all', array ('select' => 'youtube_id', 'conditions' => array ('youtube_id != ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_tag_id))), 'youtube_id')) && ($prev = Youtube::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $youtube_ids, self::IS_ENABLED))))))) || (!($tag_id || ($prev = Youtube::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id >= ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $this->id, self::IS_ENABLED)))) || ($is_loop && ($prev = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, self::IS_ENABLED))))))))
      $prev = null;

    return $this->prev[$tag_id] = $prev;
  }
  public function also ($tag_id = 0, $limit = 5) {
    $tag_id = is_object ($tag_id) ? $tag_id->id : $tag_id;
    if (isset ($this->also[$tag_id])) return $this->also[$tag_id];

    if (!($tag_id && ($tag = YoutubeTag::find_by_id ($tag_id, array ('select' => 'id'))) && ($youtube_ids = column_array (YoutubeTagMapping::find ('all', array ('select' => 'youtube_id', 'limit' => $limit + 1, 'order' => 'youtube_id DESC', 'conditions' => array ('youtube_tag_id = ?', $tag->id))), 'youtube_id')) && ($also = Youtube::find ('all', array ('order' => 'id DESC', 'limit' => $limit, 'conditions' => array ('id != ? AND id IN (?) AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, $youtube_ids, self::IS_ENABLED))))) && !($tag_id || ($also = Youtube::find ('all', array ('order' => 'id DESC', 'limit' => $limit, 'conditions' => array ('id != ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->id, self::IS_ENABLED))))))
      $also = array ();

    return $this->also[$tag_id] = $also;
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


















  // public function mini_keywords ($length = 50) {
  //   return mb_strimwidth ($this->keywords, 0, $length, '…','UTF-8');
  // }
  // public function mini_content ($length = 100) {
  //   return $length ? mb_strimwidth (remove_ckedit_tag ($this->content), 0, $length, '…','UTF-8') : remove_ckedit_tag ($this->content);
  // }
  // public function keywords () {
  //   return preg_split ("/\s+/", $this->keywords);
  // }
  // public function destroy () {
  //   if ($this->mappings)
  //     foreach ($this->mappings as $mapping)
  //       if (!$mapping->destroy ())
  //         return false;
    
  //   if ($this->sources)
  //     foreach ($this->sources as $source)
  //       if (!$source->destroy ())
  //         return false;

  //   return $this->cover->cleanAllFiles () && $this->delete ();
  // }
  // public function next ($tag_name = '') {
  //   if ($this->next !== '') return $this->next;
    
  //   if (!$tag_name) {
  //     if (!($next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ? AND id <= ?', $this->id, $this->id)))))
  //       $next = Youtube::find ('one', array ('order' => 'id DESC', 'conditions' => array ('id != ?', $this->id)));
  //   } else {
  //     if (!(($tag = YoutubeTag::find_by_name ($tag_name, array ('select' => 'id'))) && ($mapping = YoutubeTagMapping::find ('one', array ('conditions' => array ('youtube_id = ? AND youtube_tag_id = ?', $this->id, $tag->id))))))
  //       return $this->next = null;

  //     if (!($next = YoutubeTagMapping::find ('one', array ('order' => 'sort DESC', 'conditions' => array ('youtube_id != ? AND sort <= ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->sort, $mapping->youtube_tag_id)))))
  //       $next = YoutubeTagMapping::find ('one', array ('order' => 'sort DESC', 'conditions' => array ('youtube_id != ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_tag_id)));
      
  //     if (!($next && ($next = Youtube::find ('one', array ('conditions' => array ('id = ?', $next->youtube_id))))))
  //       return $this->next = null;
  //   }

  //   return $this->next = $next;
  // }
  // public function prev ($tag_name = '') {
  //   if ($this->prev !== '') return $this->prev;

  //   if (!$tag_name) {
  //     if (!($prev = Youtube::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ? AND id >= ?', $this->id, $this->id)))))
  //       $prev = Youtube::find ('one', array ('order' => 'id ASC', 'conditions' => array ('id != ?', $this->id)));
  //   } else {
  //     if (!(($tag = YoutubeTag::find_by_name ($tag_name, array ('select' => 'id'))) && ($mapping = YoutubeTagMapping::find ('one', array ('conditions' => array ('youtube_id = ? AND youtube_tag_id = ?', $this->id, $tag->id))))))
  //       return $this->prev = null;

  //     if (!($prev = YoutubeTagMapping::find ('one', array ('order' => 'sort ASC', 'conditions' => array ('youtube_id != ? AND sort >= ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->sort, $mapping->youtube_tag_id)))))
  //       $prev = YoutubeTagMapping::find ('one', array ('order' => 'sort ASC', 'conditions' => array ('youtube_id != ? AND youtube_tag_id = ?', $mapping->youtube_id, $mapping->youtube_tag_id)));

  //     if (!($prev && ($prev = Youtube::find ('one', array ('conditions' => array ('id = ?', $prev->youtube_id))))))
  //       return $this->prev = null;
  //   }

  //   return $this->prev = $prev;
  // }
  // public function site_content_page_last_uri () {
  //   return $this->id . '-' . oa_url_encode ($this->title);
  // }
  // public function cover_color ($type = 'rgba', $alpha = 1) {
  //   if (!(isset ($this->cover_color_r) && isset ($this->cover_color_r) && isset ($this->cover_color_g)))
  //     return '';

  //   $alpha = $alpha <= 1 ? $alpha >= 0 ? $alpha : 0 : 1;

  //   switch ($type) {
  //     default:
  //     case 'rgba':
  //       return 'rgba(' . $this->cover_color_r . ', ' . $this->cover_color_r . ', ' . $this->cover_color_g . ', ' . $alpha . ')';
  //       break;
  //     case 'rgb':
  //       return 'rgb(' . $this->cover_color_r . ', ' . $this->cover_color_r . ', ' . $this->cover_color_g . ')';
  //       break;
  //     case 'hex':
  //       return '#' . cover_color_hex ($this->cover_color_r) . '' . cover_color_hex ($this->cover_color_r) . '' . cover_color_hex ($this->cover_color_g);
  //       break;
  //   }
  // }

  // public function update_cover_color_and_dimension ($image_utility = null) {
  //   if (!(isset ($this->id) && isset ($this->cover) && isset ($this->cover_width) && isset ($this->cover_height)))
  //     return false;

  //   if (!(isset ($this->id) && isset ($this->cover) && isset ($this->cover_color_r) && isset ($this->cover_color_g) && isset ($this->cover_color_b)))
  //     return false;

  //   if (!$image_utility)
  //     switch (Cfg::system ('orm_uploader', 'uploader', 'driver')) {
  //       case 'local':
  //         if (!file_exists ($fileName = FCPATH . implode ('/', $this->cover->path ())))
  //           return false;

  //         $image_utility = ImageUtility::create ($fileName);
  //         break;

  //       case 's3':
  //         if (!(@S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $this->cover->path ()), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$this->cover)))) && file_exists ($fileName = FCPATH . implode ('/', $fileName))))
  //           return false;
  //         $image_utility = ImageUtility::create ($fileName);
  //         break;

  //       default:
  //         return false;
  //         break;
  //     }

  //   $return = true;
  //   $return &= $this->update_dimension ($image_utility);
  //   $return &= $this->update_cover_color ($image_utility);

  //   return $return;
  // }
  // public function update_dimension ($image_utility = null) {
  //   if (!(isset ($this->id) && isset ($this->cover) && isset ($this->cover_width) && isset ($this->cover_height)))
  //     return false;

  //   if (!$image_utility)
  //     switch (Cfg::system ('orm_uploader', 'uploader', 'driver')) {
  //       case 'local':
  //         if (!file_exists ($fileName = FCPATH . implode ('/', $this->cover->path ())))
  //           return false;

  //         $image_utility = ImageUtility::create ($fileName);
  //         break;

  //       case 's3':
  //         if (!(@S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $this->cover->path ()), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$this->cover)))) && file_exists ($fileName = FCPATH . implode ('/', $fileName))))
  //           return false;
  //         $image_utility = ImageUtility::create ($fileName);
  //         break;

  //       default:
  //         return false;
  //         break;
  //     }
  //   if (!ImageUtility::verifyDimension ($dimension = $image_utility->getDimension ()))
  //     return false;

  //   $this->cover_width = $dimension['width'];
  //   $this->cover_height = $dimension['height'];

  //   if (in_array (Cfg::system ('orm_uploader', 'uploader', 'driver'), array ('s3')))
  //     @unlink ($fileName);

  //   return $this->save ();
  // }
  // public function update_cover_color ($image_utility = null) {
  //   if (!(isset ($this->id) && isset ($this->cover) && isset ($this->cover_color_r) && isset ($this->cover_color_g) && isset ($this->cover_color_b)))
  //     return false;

  //   if (!$image_utility)
  //     switch (Cfg::system ('orm_uploader', 'uploader', 'driver')) {
  //       case 'local':
  //         if (!file_exists ($fileName = FCPATH . implode ('/', $this->cover->path ())))
  //           return false;

  //         $image_utility = ImageUtility::create ($fileName);
  //         break;

  //       case 's3':
  //         if (!(@S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $this->cover->path ()), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge (Cfg::system ('orm_uploader', 'uploader', 'temp_directory'), array ((string)$this->cover)))) && file_exists ($fileName = FCPATH . implode ('/', $fileName))))
  //           return false;
  //         $image_utility = ImageUtility::create ($fileName);
  //         break;

  //       default:
  //         return false;
  //         break;
  //     }

  //   if (!(($analysis_datas = $image_utility->resize (10, 10, 'w')->getAnalysisDatas (1)) && isset ($analysis_datas[0]['color']) && ($analysis_datas = $analysis_datas[0]['color']) && (isset ($analysis_datas['r']) && isset ($analysis_datas['g']) && isset ($analysis_datas['b']))))
  //     return false;

  //   $average = 128;

  //   $red = round ($analysis_datas['r'] / 10) * 10; $green = round ($analysis_datas['g'] / 10) * 10; $blue = round ($analysis_datas['b'] / 10) * 10;
  //   $red += (round (($red - $average) / 10) * 1.125) * 10; $green += (round (($green - $average) / 10) * 1.125) * 10; $blue += (round (($blue - $average) / 10) * 1.125) * 10;
  //   $red = round ($red > 0 ? $red < 256 ? $red : 255 : 0); $green = round ($green > 0 ? $green < 256 ? $green : 255 : 0); $blue = round ($blue > 0 ? $blue < 256 ? $blue : 255 : 0);
  //   $this->cover_color_r = max (0, min ($red, 255)); $this->cover_color_g = max (0, min ($green, 255)); $this->cover_color_b = max (0, min ($blue, 255));

  //   if (in_array (Cfg::system ('orm_uploader', 'uploader', 'driver'), array ('s3')))
  //     @unlink ($fileName);

  //   return $this->save ();
  // }
}