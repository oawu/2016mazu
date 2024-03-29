<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class ArticleCoverImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '100x100c' => array ('adaptiveResizeQuadrant', 100, 100, 'c'),
        '500w' => array ('resize', 500, 500, 'width'),
        '1200x630c' => array ('adaptiveResizeQuadrant', 1200, 630, 'c'),
      );
  }
}