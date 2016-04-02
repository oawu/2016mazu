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

  public function ckeditors () {
    $log = CrontabLog::start ('每 60 分鐘壓縮 Ckeditor Pictures 圖片');
    $pics = CkeditorPicture::find ('all', array ('select' => 'id, name, is_compressor', 'order' => 'id DESC', 'limit' => 10, 'conditions' => array ('is_compressor = 0')));

    require_once ('vendor/autoload.php');

    $ss = array ('400w', '');;
    foreach ($pics as $i => $pic) {
      foreach ($ss as $s) {
        @S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $pic->name->path ($s)), $path = FCPATH . 'temp' . DIRECTORY_SEPARATOR . $s . '_' . $pic->name);

        if (!file_exists ($path)) return $log->error ('Download Error!');
        if (!$key = keys ('tinypngs', Cfg::setting ('tinypng', 'psw'))) return $log->error ('No any key Error!');

        try {
          \Tinify\setKey ($key);
          \Tinify\validate ();

          if (!(($source = \Tinify\fromFile ($path)) && ($source->toFile ($path)))) return $log->error ('Tinify toFile Error!');
        } catch (Exception $e) { return $log->error ('Tinify try catch Error!'); }

        $s3_path = implode (DIRECTORY_SEPARATOR, array_merge ($pic->name->getBaseDirectory (), $pic->name->getSavePath ())) . DIRECTORY_SEPARATOR . $s . '_' . $pic->name;
        $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');

        if (!($source->store (array ('service' => 's3', 'aws_access_key_id' => Cfg::system ('s3', 'buckets', $bucket, 'access_key'), 'aws_secret_access_key' => Cfg::system ('s3', 'buckets', $bucket, 'secret_key'), 'region' => Cfg::system ('s3', 'buckets', $bucket, 'region'), 'path' => $bucket . DIRECTORY_SEPARATOR . $s3_path)))) return $log->error ('Put s3 Error!');
        @unlink ($path);
      }

      $pic->is_compressor = 1;
      if (!$pic->save ()) return $log->error ('Save Error!');

      $log->finish ();
    }
  }
  public function pictures () {
    $log = CrontabLog::start ('每 60 分鐘壓縮 Pictures 圖片');
    $pics = Picture::find ('all', array ('select' => 'id, name, is_compressor', 'order' => 'id DESC', 'limit' => 10, 'conditions' => array ('is_compressor = 0')));

    require_once ('vendor/autoload.php');

    $ss = array ('500w', '2048w');;
    foreach ($pics as $i => $pic) {
      foreach ($ss as $s) {
        @S3::getObject (Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket'), implode (DIRECTORY_SEPARATOR, $pic->name->path ($s)), $path = FCPATH . 'temp' . DIRECTORY_SEPARATOR . $s . '_' . $pic->name);

        if (!file_exists ($path)) return $log->error ('Download Error!');
        if (!$key = keys ('tinypngs', Cfg::setting ('tinypng', 'psw'))) return $log->error ('No any key Error!');

        try {
          \Tinify\setKey ($key);
          \Tinify\validate ();

          if (!(($source = \Tinify\fromFile ($path)) && ($source->toFile ($path)))) return $log->error ('Tinify toFile Error!');
        } catch (Exception $e) { return $log->error ('Tinify try catch Error!'); }

        $s3_path = implode (DIRECTORY_SEPARATOR, array_merge ($pic->name->getBaseDirectory (), $pic->name->getSavePath ())) . DIRECTORY_SEPARATOR . $s . '_' . $pic->name;
        $bucket = Cfg::system ('orm_uploader', 'uploader', 's3', 'bucket');

        if (!($source->store (array ('service' => 's3', 'aws_access_key_id' => Cfg::system ('s3', 'buckets', $bucket, 'access_key'), 'aws_secret_access_key' => Cfg::system ('s3', 'buckets', $bucket, 'secret_key'), 'region' => Cfg::system ('s3', 'buckets', $bucket, 'region'), 'path' => $bucket . DIRECTORY_SEPARATOR . $s3_path)))) return $log->error ('Put s3 Error!');
        @unlink ($path);
      }

      $pic->is_compressor = 1;
      if (!$pic->save ()) return $log->error ('Save Error!');

      $log->finish ();
    }
  }
}
