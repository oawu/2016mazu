<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class DintaoCoverImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '40x40c' => array ('adaptiveResizeQuadrant', 40, 40, 't'),
        '180x130c' => array ('adaptiveResizeQuadrant', 180, 130, 't'),
        '1200x630c' => array ('adaptiveResizeQuadrant', 1200, 630, 't'),
      );
  }
}