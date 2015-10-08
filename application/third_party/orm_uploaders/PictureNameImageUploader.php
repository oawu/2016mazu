<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class PictureNameImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '30x30c' => array ('adaptiveResizeQuadrant', 40, 40, 't'),
        '50x50c' => array ('adaptiveResizeQuadrant', 50, 50, 't'),
        '300w'   => array ('resize', 300, 300, 'width'),
        '1200x630c' => array ('adaptiveResizeQuadrant', 1200, 630, 't'),
      );
  }
}