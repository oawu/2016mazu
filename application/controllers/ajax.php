<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Ajax extends Site_controller {

  public function __construct () {
    parent::__construct ();

    if (!$this->input->is_ajax_request ())
      return show_404 ();
  }

  public function navbar () {
    $type = ($type = OAInput::get ('type')) && in_array ($type, array ('site', 'admin')) ? $type : 'site';

    $menus = array ();
    if ($type == 'admin') {
      array_push ($menus, array ('text' => '前台', 'class' => 'icon-home', 'href' => base_url ()));
      array_push ($menus, array ('text' => '登出', 'class' => 'icon-exit top_line logout', 'href' => Fb::logoutUrl ('platform', 'sign_out')));
    }

    if ($type == 'site') {
      array_push ($menus, array ('text' => '分享', 'class' => 'icon-share share', 'href' => ''));
      
      if (!User::current ())
        array_push ($menus, array ('text' => '登入', 'class' => 'icon-enter top_line login', 'href' => Fb::loginUrl ('platform', 'fb_sign_in')));
      else {
        if (array_intersect (Cfg::setting ('admin', 'roles'), User::current ()->roles ()))
          array_push ($menus, array ('text' => '管理', 'class' => 'icon-user top_line admin', 'href' => base_url ('admin')));
        
        array_push ($menus, array ('text' => '登出', 'class' => 'icon-exit logout', 'href' => Fb::logoutUrl ('platform', 'sign_out')));
      }
    }

    $content = $this->load_content (array (
        'type' => $type,
        'menus' => $menus,
      ), true);

    return $this->output_json (array ('status' => true, 'content' => $content));
  }

  public function pv () {
    if (!(($id = OAInput::post ('id')) && ($class = OAInput::post ('class')) && class_exists ($class) && in_array ($class, array ('Dintao', 'Picture'))))
      return show_404 ();

    if (!($obj = $class::find_by_id ($id, array ('select' => 'id, pv'))))
      return $this->output_json (array ('status' => false));

    $obj->pv += 1;

    $update = $class::transaction (function () use ($obj) {
      return $obj->save ();
    });

    if (!$update)
      return $this->output_json (array ('status' => false));

    return $this->output_json (array ('status' => true, 'pv' => $obj->pv));
  }
}
