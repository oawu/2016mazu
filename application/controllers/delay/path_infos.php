<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Path_infos extends Delay_controller {

  public function update_image_cover_color () {
    if (!(($id = OAInput::post ('id')) && ($info = PathInfo::find_by_id ($id, array ('select' => 'id, image, latitude, longitude, cover, cover_color_r, cover_color_g, cover_color_b')))))
      return ;

    $info->put_image ();
    $info->update_cover_color ();
  }

  public function update_image () {
    if (!(($id = OAInput::post ('id')) && ($info = PathInfo::find_by_id ($id, array ('select' => 'id, image, latitude, longitude')))))
      return ;

    $info->put_image ();
  }
}
