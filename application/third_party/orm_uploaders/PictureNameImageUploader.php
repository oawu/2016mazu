<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class PictureNameImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '100x100c'  => array ('adaptiveResizeQuadrant', 100, 100, 't'),
        '500w' => array ('resize', 500, 500, 'width'),
        '2048w' => array ('resize', 2048, 2048, 'width'),
        '1200x630c' => array ('adaptiveResizeQuadrant', 1200, 630, 't'),
      );
  }
}