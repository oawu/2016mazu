<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintao_cell extends Cell_Controller {

  /* render_cell ('dintao_cell', 'dintaos', var1, ..); */
  // public function _cache_list () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function dintaos ($class, $method, $offset) {
    $index = $method != 'official' ? $method != 'local' ? Dintao::TYPE_OTHER : Dintao::TYPE_LOCAL : Dintao::TYPE_OFFICIAL;

    $columns = array ('title' => 'string', 'content' => 'string', 'keywords' => 'string');
    $configs = array ($class, $method, '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Dintao', OAInput::get ())));
    Dintao::addConditions ($conditions, 'type = ?', $index);

    $limit = 10;
    $total = Dintao::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->CI->load->library ('pagination');
    $pagination = $this->CI->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $dintaos = Dintao::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'sort DESC',
        'conditions' => $conditions
      ));

    return $this->setUseCssList (true)
                ->load_view (array (
        'dintaos' => $dintaos,
        'pagination' => $pagination,
      ));
  }
}