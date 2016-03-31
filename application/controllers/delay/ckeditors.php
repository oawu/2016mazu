<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Ckeditors extends Delay_controller {

  public function compressor () {
    if (!(($id = OAInput::post ('id')) && ($cke = CkeditorPicture::find_by_id ($id, array ('select' => 'id, name')))))
      return ;

    $cke->name->compressor ();
  }
}
