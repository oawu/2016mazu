{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Demo_cell extends Cell_Controller {

  /* render_cell ('demo_cell', 'main_menu', var1, ..); */
  // public function _cache_main_menu () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function main_menu () {

    // 使用 view 的 css，但不使用 js
    // 回傳 load cell view 的結果
    return $this->setUseJsList (false)
                ->setUseCssList (true)
                ->load_view ();
  }
}