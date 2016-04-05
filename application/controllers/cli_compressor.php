<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Cli_compressor extends Site_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }

  public function pictures () {
    $log = CrontabLog::start ('每 60 分鐘壓縮 Pictures 圖片');
    if ($return = $this->_compressor ('Picture', array ('500w', '2048w'), 'name', 'is_compressor'))
      return $log->error ($return);
    $log->finish ();
  }
  public function ckeditors () {
    $log = CrontabLog::start ('每 60 分鐘壓縮 Ckeditor Pictures 圖片');
    if ($return = $this->_compressor ('CkeditorPicture', array ('400w', ''), 'name', 'is_compressor'))
      return $log->error ($return);
    $log->finish ();
  }

  private function _compressor ($model, $sizes = array (), $column, $flog_column, $limit = 10) {
    if (!$pics = $model::find ('all', array ('select' => 'id, ' . $column . ', ' . $flog_column, 'order' => 'id DESC', 'limit' => $limit, 'conditions' => array ($flog_column . ' = 0'))))
      return '';

    require_once ('vendor/autoload.php');

    foreach ($pics as $i => $pic) {
      foreach ($sizes as $size) {
        @S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $pic->$column->path ($size)), $path = FCPATH . 'temp' . DIRECTORY_SEPARATOR . $size . '_' . $pic->$column);

        if (!file_exists ($path)) return 'Download Error!';
        if (!$key = keys ('tinypngs', Cfg::setting ('tinypng', 'psw'))) return 'No any key Error!';

        try {
          \Tinify\setKey ($key);
          \Tinify\validate ();

          if (!(($source = \Tinify\fromFile ($path)) && ($source->toFile ($path)))) return 'Tinify toFile Error!';
        } catch (Exception $e) { return 'Tinify try catch Error!'; }

        $s3_path = implode (DIRECTORY_SEPARATOR, array_merge ($pic->$column->getBaseDirectory (), $pic->$column->getSavePath ())) . DIRECTORY_SEPARATOR . $size . '_' . $pic->$column;
        $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');

        if (!($source->store (array ('service' => 's3', 'aws_access_key_id' => Cfg::system ('s3', 'buckets', $bucket, 'access_key'), 'aws_secret_access_key' => Cfg::system ('s3', 'buckets', $bucket, 'secret_key'), 'region' => Cfg::system ('s3', 'buckets', $bucket, 'region'), 'path' => $bucket . DIRECTORY_SEPARATOR . $s3_path)))) return 'Put s3 Error!';
        @unlink ($path);
      }

      $pic->$flog_column = 1;
      if (!$pic->save ()) return 'Save Error!';
    }
    return '';
  }
}
