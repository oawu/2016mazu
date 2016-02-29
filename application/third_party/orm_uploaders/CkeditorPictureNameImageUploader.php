<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class CkeditorPictureNameImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '50w' => array ('resize', 50, 50, 'width'),
        '400w' => array ('resize', 400, 400, 'width'),
      );
  }
}