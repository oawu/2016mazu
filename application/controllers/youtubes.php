<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Youtubes extends Site_controller {

  public function content ($method = 'all', $id = 0) {
    if (!($id && ($youtube = Youtube::find_by_id ($id))))
      return redirect_message (array (''), array (
          '_flash_message' => '無此照片！'
        ));

    if (!preg_match ('/^data:/', $og_img = $youtube->cover->url ('1200x630c')))
      $this->add_meta (array ('property' => 'og:image', 'content' => $og_img, 'alt' => $youtube->title . ' - ' . Cfg::setting ('site', 'main', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'content' => 'image/' . pathinfo ($og_img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'content' => '630'));

    return $this->set_title ($youtube->title . ' - ' . Cfg::setting ('site', 'main', 'title'))
                ->set_subtitle ($youtube->title)
                ->set_back_link (base_url ($this->get_class (), $method))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $youtube->keywords ())))
                ->add_meta (array ('name' => 'description', 'content' => $youtube->mini_description ()))
                ->add_meta (array ('property' => 'og:title', 'content' => $youtube->title))
                ->add_meta (array ('property' => 'og:description', 'content' => $youtube->mini_description ()))
                ->add_hidden (array ('id' => 'id', 'value' => $youtube->id))
                ->load_view (array (
                    'method' => $method,
                    'youtube' => $youtube
                  ), false);
  }
  public function all ($offset = 0, $keyword = '') {
    $keyword = trim (urldecode ($keyword));

    $columns = array ('id' => 'int');
    $configs = array ($this->get_class (), 'all', '%s', $keyword);
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Youtube', OAInput::get ())));
    if ($keyword) Youtube::addConditions ($conditions, '(title LIKE ?) OR (description LIKE ?) OR (keywords LIKE ?)', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%');

    $limit = 12;
    $total = Youtube::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs['uri_segment'] = 3;
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $youtubes = Youtube::find ('all', array (
      'offset' => $offset,
      'limit' => $limit,
      'order' => 'id DESC',
      'conditions' => $conditions));

    return $this->set_method ('index')
                ->set_subtitle ($keyword ? '<span class="icon-search"></span>' . $keyword : '所有照片')
                ->load_view (array (
                    'has_photoswipe' => true,
                    'method' => 'all',
                    'youtubes' => $youtubes,
                    'pagination' => $pagination
                  ));
  }
  public function index ($method = '', $offset = 0) {
    if (!($tag = YoutubeTag::find_by_name ($method, array ('select' => 'id'))))
      return $this->set_subtitle ($method)
                  ->load_view (array (
                      'has_photoswipe' => false,
                      'method' => $method,
                      'youtubes' => array (),
                      'pagination' => ''
                    ));

    $columns = array ('id' => 'int');
    $configs = array ($this->get_class (), $method, '%s');
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'YoutubeTagMapping', OAInput::get ())));
    YoutubeTagMapping::addConditions ($conditions, 'youtube_tag_id = ?', $tag->id);

    $limit = 12;
    $total = YoutubeTagMapping::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $youtube_ids = column_array (YoutubeTagMapping::find ('all', array (
      'select' => 'youtube_id',
      'offset' => $offset,
      'limit' => $limit,
      'order' => 'sort DESC',
      'conditions' => $conditions)), 'youtube_id');

    $youtubes = $youtube_ids ? Youtube::find ('all', array ('conditions' => array ('id IN (?)', $youtube_ids))) : array ();

    return $this
                ->set_subtitle ($method)
                ->load_view (array (
                    'has_photoswipe' => true,
                    'method' => $method,
                    'youtubes' => $youtubes,
                    'pagination' => $pagination
                  ));
  }
}
