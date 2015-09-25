<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class CkeditorImageNameImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '50w' => array ('resize', 50, 50, 'width'),
        '900w' => array ('resize', 900, 900, 'width'),
      );
  }
}