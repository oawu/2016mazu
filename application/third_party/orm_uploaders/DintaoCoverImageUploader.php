<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class DintaoCoverImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '40x40c' => array ('adaptiveResizeQuadrant', 40, 40, 'c'),
      );
  }
}