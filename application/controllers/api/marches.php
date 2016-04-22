<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Marches extends Api_controller {
  
  private $march = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->march = March::find ('one', array ('conditions' => array ('id = ?', $id))))))
        return $this->disable ($this->output_error_json ('Parameters error!'));
  }


  public function update () {
    if (!$this->has_post ())
      return $this->disable ($this->output_error_json ('Parameters error!'));
      
    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return $this->disable ($this->output_error_json ($msg));

    if ($columns = array_intersect_key ($posts, $this->march->table ()->columns))
      foreach ($columns as $column => $value)
        $this->march->$column = $value;
    
    $march = $this->march;
    $update = March::transaction (function () use ($march, $posts) {
      if (!$march->save ())
        return false;
      return true;
    });

    return $this->output_json ($march->to_array ());
  }
  public function index () {
    $marches = array_map (function ($march) {
      return array_merge ($march->to_array (), array ('b' => $march->last_path ? $march->last_path->battery : -1));
    }, March::find ('all', array ('select' => 'id,title AS t,is_enabled AS e')));

    return $this->output_json ($marches);
  }

  private function _validation_posts (&$posts) {
    if (isset ($posts['is_enabled']))
      if (!(is_numeric ($posts['is_enabled']) && in_array ($posts['is_enabled'], array_keys (March::$isIsEnabledNames))))
        return 'is_enabled 錯誤！';

    if (isset ($posts['distance']))
      if (!is_numeric ($posts['distance']))
        return 'distance 錯誤！';

    return '';
  }
}
