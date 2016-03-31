<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Others extends Delay_controller {

  public function update_cover_color_and_dimension () {
    if (!(($id = OAInput::post ('id')) && ($other = Other::find_by_id ($id, array ('select' => 'id, cover, cover_color_r, cover_color_g, cover_color_b, cover_width, cover_height')))))
      return ;

    $other->update_cover_color_and_dimension ();
    if (ENVIRONMENT == 'production')
      $other->cover->compressor ();
  }
}
