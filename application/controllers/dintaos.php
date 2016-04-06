<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Dintaos extends Site_controller {
  private $tag = null;

  public function __construct () {
    parent::__construct ();

    $this->add_param ('tag', $this->tag)
         ;
  }
  public function show ($id = 0) {
    if (!($id && ($dintao = Dintao::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $id, Dintao::IS_ENABLED))))))
      return redirect_message (array ('dintaos'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if ($tags = array_unique (array_merge (column_array ($dintao->tags, 'name'), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    if ($also = $dintao->also ())
      foreach ($also as $i => $a)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => $a->content_page_url ($this->tag)));

    return $this->set_title ($dintao->title . ' - ' . Cfg::setting ('site', 'title'))
                ->set_subtitle ($dintao->title)
                ->set_back_link (base_url ('dintaos'))
                ->add_css (resource_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
                ->add_js (resource_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'my.js'))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_unique (array_merge ($dintao->keywords (), Cfg::setting ('site', 'keywords'))))))
                ->add_meta (array ('name' => 'description', 'content' => $dintao->mini_content (150)))
                ->add_meta (array ('property' => 'og:title', 'content' => $dintao->title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:description', 'content' => $dintao->mini_content (300)))
                ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $dintao->cover->url ('1200x630c'), 'alt' => $dintao->title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
                ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
                ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
                ->add_meta (array ('property' => 'article:modified_time', 'content' => $dintao->updated_at->format ('c')))
                ->add_meta (array ('property' => 'article:published_time', 'content' => $dintao->created_at->format ('c')))
                ->add_hidden (array ('id' => 'id', 'value' => $dintao->id))
                ->load_view (array (
                    'dintao' => $dintao,
                    'prev' => $dintao->prev ($this->tag),
                    'next' => $dintao->next ($this->tag),
                  ), false, 60);
  }
  public function index ($offset = 0) {
    $columns = array ();

    $configs = array ('dintaos', '%s');
    $conditions = conditions ($columns, $configs);
    Dintao::addConditions ($conditions, 'destroy_user_id IS NULL AND is_enabled = ?', Dintao::IS_ENABLED);

    $limit = 10;
    $total = Dintao::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $dintaos = Dintao::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    if ($tags = DintaoTag::all (array ('select' => 'id', 'limit' => 5, 'conditions' => array ('is_on_site = ?', DintaoTag::IS_ON_SITE_NAMES))))
      foreach ($tags as $tag)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('tag', $tag->id, 'dintaos')));

    $title = '百年藝陣';
    $tag_names = column_array (DintaoTag::all (array ('select' => 'name', 'order' => 'RAND()', 'limit' => 10)), 'name');
    if ($tags = array_unique (array_merge (array ($title), $tag_names, Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));

    if ($dintaos && ($dintao = $dintaos[rand(0, count ($dintaos) - 1)]))
      $this->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $dintao->cover->url ('1200x630c'), 'alt' => $dintao->title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
           ->add_meta (array ('property' => 'article:modified_time', 'content' => $dintao->updated_at->format ('c')))
           ->add_meta (array ('property' => 'article:published_time', 'content' => $dintao->created_at->format ('c')));

    return $this->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
                ->set_subtitle ($title)
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
                ->add_meta (array ('name' => 'description', 'content' => implode (' ', array_merge (array (Cfg::setting ('site', 'title'), $title), column_array ($dintaos, 'title')))))
                ->add_meta (array ('property' => 'og:title', 'content' => $title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:description', 'content' => implode (' ', array_merge (array (Cfg::setting ('site', 'title'), $title), column_array ($dintaos, 'title')))))
                ->load_view (array (
                    'dintaos' => $dintaos,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ), false, 60);
  }
}
