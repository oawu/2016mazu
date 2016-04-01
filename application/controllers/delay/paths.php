<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Paths extends Delay_controller {

  public function update_image_and_length () {
    if (!(($id = OAInput::post ('id')) && ($path = Path::find_by_id ($id, array ('select' => 'id, image, length')))))
      return ;

    $path->update_image ();
    $path->update_length ();
    // if (ENVIRONMENT == 'production')
    //   $path->image->compressor ();
  }
}
