<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Youtubes extends Delay_controller {

  public function update_cover_color_and_dimension () {
    if (!(($id = OAInput::post ('id')) && ($youtube = Youtube::find_by_id ($id, array ('select' => 'id, cover, cover_color_r, cover_color_g, cover_color_b, cover_width, cover_height')))))
      return ;

    $youtube->update_cover_color_and_dimension ();
  }
}
