<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Settings extends Api_controller {
  
  private $setting = null;

  public function __construct () {
    parent::__construct ();

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'show')))
      if (!(($id = $this->uri->rsegments (3, 0)) && ($this->setting = GpsSetting::find ('one', array ('conditions' => array ('id = ?', $id))))))
        return $this->disable ($this->output_error_json ('Parameters error!'));
  }
  public function show () {
    return $this->output_json ($this->setting->to_array ());
  }
  public function update () {
    if (!$this->has_post ())
      return $this->disable ($this->output_error_json ('Parameters error!'));
      
    $posts = OAInput::post ();

    if ($msg = $this->_validation_posts ($posts))
      return $this->disable ($this->output_error_json ($msg));

    if ($columns = array_intersect_key ($posts, $this->setting->table ()->columns))
      foreach ($columns as $column => $value)
        $this->setting->$column = $value;
    
    $setting = $this->setting;
    $update = GpsSetting::transaction (function () use ($setting, $posts) {
      if (!$setting->save ())
        return false;
      return true;
    });

    return $this->output_json ($setting->to_array ());
  }
  private function _validation_posts (&$posts) {
    if (isset ($posts['is_crontab']))
      if (!(is_numeric ($posts['is_crontab']) && in_array ($posts['is_crontab'], array_keys (GpsSetting::$isIsCrontabNames))))
        return 'is_crontab 錯誤！';

    if (isset ($posts['path_id']))
      if (!(is_numeric ($posts['path_id']) && in_array ($posts['path_id'], column_array (Path::find ('all', array ('select' => 'id')), 'id'))))
        return 'path_id 錯誤！';

    if (isset ($posts['version']))
      if (!is_numeric ($posts['version']))
        return 'version 錯誤！';

    return '';
  }
}
