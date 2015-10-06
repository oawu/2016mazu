<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintaos extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index ($index = Dintao::TYPE_OTHER, $offset = 0) {
    $columns = array ('title' => 'string', 'content' => 'string', 'keywords' => 'string');
    $configs = array ($this->get_class (), Dintao::$type_engs[$index], '%s');

    $conditions = array (implode (' AND ', conditions ($columns, $configs, 'Dintao', OAInput::get ())));
    Dintao::addConditions ($conditions, 'type = ?', $index);

    $limit = 10;
    $total = Dintao::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $dintaos = Dintao::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'sort DESC',
        'conditions' => $conditions
      ));

    return $this->add_subtitle (Dintao::$types[$index])
                ->load_view (array (
                    'method' => Dintao::$type_engs[$index],
                    'dintaos' => $dintaos,
                    'pagination' => $pagination
                  ));
  }

  public function content ($id = 0) {
    if (!($id && ($dintao = Dintao::find_by_id ($id))))
      return redirect_message (array ('dintaos', 'official'), array (
          '_flash_message' => '無此文章！'
        ));

    $method = $dintao->type != Dintao::TYPE_OTHER ? $dintao->type != Dintao::TYPE_LOCAL ? 'official' : 'local' : 'other';
    
    if (!preg_match ('/^data:/', $og_img = $dintao->cover->url ('1200x630c')))
      $this->add_meta (array ('property' => 'og:image', 'content' => $og_img, 'alt' => $dintao->title . ' - ' . Cfg::setting ('site', 'main', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'content' => 'image/' . pathinfo ($og_img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'content' => '630'));

    return $this->set_title ($dintao->title . ' - ' . Cfg::setting ('site', 'main', 'title'))
                ->add_subtitle ($dintao->title)
                ->set_back_link (base_url ($this->get_class (), $method))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $dintao->keywords ())))
                ->add_meta (array ('name' => 'description', 'content' => $dintao->mini_content ()))
                ->add_meta (array ('property' => 'og:title', 'content' => $dintao->title))
                ->add_meta (array ('property' => 'og:description', 'content' => $dintao->mini_content ()))

                ->load_view (array (
                    'method' => $method,
                    'dintao' => $dintao
                  ), false);
  }
}
