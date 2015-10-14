<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Pictures extends Delay_controller {

  public function update_color_dimension () {
    if (!(($id = OAInput::post ('id')) && ($picture = Picture::find_by_id ($id, array ('select' => 'id, name, color_r, color_g, color_b, width, height')))))
      return ;

    $picture->update_color_dimension ();
  }
}
