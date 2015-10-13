<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pictures extends Site_controller {

  public function index ($method = '', $offset = 0) {
    $tag_id = $method == 'old' ? 5 : 6;

    $columns = array ('id' => 'int');
    $configs = array ($this->get_class (), 'old', '%s');
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Picture', OAInput::get ())));
    PictureTagMapping::addConditions ($conditions, 'picture_tag_id = ?', $tag_id);

    $limit = 25;
    $total = PictureTagMapping::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $picture_ids = column_array (PictureTagMapping::find ('all', array (
      'select' => 'picture_id',
      'offset' => $offset,
      'limit' => $limit,
      'order' => 'sort DESC',
      'conditions' => $conditions)), 'picture_id');

    $pictures = $picture_ids ? Picture::find ('all', array ('conditions' => array ('id IN (?)', $picture_ids))) : array ();

    return $this->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox.css'))
                ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.css'))
                ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.css'))
                ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'pictures.css'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox.js'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.js'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.js'))
                ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-media.js'))
                
                ->add_subtitle ('')
                ->load_view (array (
                    'method' => $method,
                    'pictures' => $pictures,
                    'pagination' => $pagination
                  ));
  }
  // public function old ($offset = 0) {
    
  // }
  // public function march19_2015 () {

  // }
}