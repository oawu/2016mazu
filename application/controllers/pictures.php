<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pictures extends Site_controller {

  public function content ($method = 'all', $id = 0) {
    if (!($id && ($picture = Picture::find_by_id ($id))))
      return redirect_message (array ('pictures'), array (
          '_flash_message' => '無此照片！'
        ));

    $next = $picture->next ($method == 'all' ? '' : $method);
    $prev = $picture->prev ($method == 'all' ? '' : $method);

    if (!preg_match ('/^data:/', $og_img = $picture->name->url ('1200x630c')))
      $this->add_meta (array ('property' => 'og:image', 'content' => $og_img, 'alt' => $picture->title . ' - ' . Cfg::setting ('site', 'main', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'content' => 'image/' . pathinfo ($og_img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'content' => '630'));

    return $this->set_title ($picture->title . ' - ' . Cfg::setting ('site', 'main', 'title'))
                ->set_subtitle ($picture->title)
                ->set_back_link (base_url ($this->get_class (), $method))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $picture->keywords ())))
                ->add_meta (array ('name' => 'description', 'content' => $picture->mini_description ()))
                ->add_meta (array ('property' => 'og:title', 'content' => $picture->title))
                ->add_meta (array ('property' => 'og:description', 'content' => $picture->mini_description ()))
                ->add_hidden (array ('id' => 'id', 'value' => $picture->id))
                ->load_view (array (
                    'method' => $method,
                    'picture' => $picture,
                    'next' => $next,
                    'prev' => $prev,
                  ), false);
  }
  public function all ($offset = 0, $keyword = '') {
    $keyword = trim (urldecode ($keyword));

    $columns = array ('id' => 'int');
    $configs = array ($this->get_class (), 'all', '%s', $keyword);
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Picture', OAInput::get ())));
    if ($keyword) Picture::addConditions ($conditions, '(title LIKE ?) OR (description LIKE ?) OR (keywords LIKE ?)', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%');

    $limit = 12;
    $total = Picture::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs['uri_segment'] = 3;
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $pictures = Picture::find ('all', array (
      'offset' => $offset,
      'limit' => $limit,
      'order' => 'id DESC',
      'conditions' => $conditions));

    return $this->set_method ('index')
                ->add_css (base_url ('resource', 'css', 'photoswipe_v4.1.0', 'photoswipe.css'))
                ->add_css (base_url ('resource', 'css', 'photoswipe_v4.1.0', 'oa-skin.css'))
                ->add_js (base_url ('resource', 'javascript', 'photoswipe_v4.1.0', 'photoswipe.min.js'))
                ->add_js (base_url ('resource', 'javascript', 'photoswipe_v4.1.0', 'photoswipe-ui-default.min.js'))
                ->set_subtitle ($keyword ? '<span class="icon-search"></span>' . $keyword : '所有照片')
                ->load_view (array (
                    'has_photoswipe' => true,
                    'method' => 'all',
                    'pictures' => $pictures,
                    'pagination' => $pagination
                  ));
  }
  public function index ($method = '', $offset = 0) {
    if (!($tag = PictureTag::find_by_name ($method = urldecode ($method), array ('select' => 'id'))))
      return $this->set_subtitle ($method)
                  ->load_view (array (
                      'has_photoswipe' => false,
                      'method' => $method,
                      'pictures' => array (),
                      'pagination' => ''
                    ));

    $columns = array ('id' => 'int');
    $configs = array ($this->get_class (), $method, '%s');
    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'PictureTagMapping', OAInput::get ())));
    PictureTagMapping::addConditions ($conditions, 'picture_tag_id = ?', $tag->id);

    $limit = 12;
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

    $pictures = $picture_ids ? Picture::find ('all', array ('order' => 'FIELD(id, ' . implode (', ', $picture_ids) . ')', 'conditions' => array ('id IN (?)', $picture_ids))) : array ();

    return $this
                ->add_css (base_url ('resource', 'css', 'photoswipe_v4.1.0', 'photoswipe.css'))
                ->add_css (base_url ('resource', 'css', 'photoswipe_v4.1.0', 'oa-skin.css'))
                ->add_js (base_url ('resource', 'javascript', 'photoswipe_v4.1.0', 'photoswipe.min.js'))
                ->add_js (base_url ('resource', 'javascript', 'photoswipe_v4.1.0', 'photoswipe-ui-default.min.js'))
                ->set_subtitle ($method)
                ->load_view (array (
                    'has_photoswipe' => true,
                    'method' => $method,
                    'pictures' => $pictures,
                    'pagination' => $pagination
                  ));
  }
}
