<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pv extends Site_controller {

  public function dintao () {
    if (!$this->is_ajax ())
      return show_404 ();

    if (!(($id = OAInput::post ('id')) && ($dintao = Dintao::find_by_id ($id, array ('select' => 'id, pv')))))
      return $this->output_json (array ('status' => false));

    $dintao->pv += 1;

    $update = Dintao::transaction (function () use ($dintao) {
      return $dintao->save ();
    });

    if (!$update)
      return $this->output_json (array ('status' => false));

    return $this->output_json (array ('status' => true, 'pv' => $dintao->pv));
  }
  public function picture () {
    if (!$this->is_ajax ())
      return show_404 ();

    if (!(($id = OAInput::post ('id')) && ($picture = Picture::find_by_id ($id, array ('select' => 'id, pv')))))
      return $this->output_json (array ('status' => false));

    $picture->pv += 1;

    $update = Picture::transaction (function () use ($picture) {
      return $picture->save ();
    });

    if (!$update)
      return $this->output_json (array ('status' => false));

    return $this->output_json (array ('status' => true, 'pv' => $picture->pv));
  }
}
