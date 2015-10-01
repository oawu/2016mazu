<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Dintaos extends Delay_controller {

  public function update_cover_color () {
    if (!(($id = OAInput::post ('id')) && ($dintao = Dintao::find_by_id ($id, array ('select' => 'id, cover, cover_color_r, cover_color_g, cover_color_b')))))
      return ;

    $dintao->update_cover_color ();
  }
}