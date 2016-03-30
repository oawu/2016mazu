<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March_message_blacklists extends Api_controller {
  private $black = null;
  public function __construct () {
    parent::__construct ();


    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->black = MarchMessageBlacklist::find ('one', array ('conditions' => array ('id = ?', $id))))))
        return $this->disable ($this->output_error_json ('Parameters error!'));
  }

  public function index () {
    $list = MarchMessageBlacklist::all (array ('select' => 'id,ip'));

    $list = array_map (function ($item) {
      return $item->to_array ();
    }, $list);

    return $this->output_json (array ('l' => $list));
  }
  public function destroy () {
    $black = $this->black;
    $delete = ArticleTag::transaction (function () use ($black) {
      return $black->destroy ();
    });

    return $this->output_json (array ('s' => true));
  }
}
