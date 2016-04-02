<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Pictures extends Delay_controller {

  public function update_name_color_and_dimension () {
    if (!(($id = OAInput::post ('id')) && ($picture = Picture::find_by_id ($id, array ('select' => 'id, name, name_color_r, name_color_g, name_color_b, name_width, name_height')))))
      return ;

    $picture->update_name_color_and_dimension ();
  }
}
