{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class <?php echo ucfirst (camelize ($name . $class_suffix));?> extends ElasticaSearch {
  static $primary_key = 'id';
  static $type_name = '<?php echo pluralize ($name);?>';

  public function __construct ($data = array ()) {
    parent::__construct ($data);
  }
}