<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Stores extends Site_controller {
  private $tag = null;

  public function show ($id = 0) {
    if (!($id && ($store = Store::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $id, Store::IS_ENABLED))))))
      $store = null;

    return $this->set_frame_path ('frame', 'pure')
                ->load_view (array (
                    'store' => $store
                  ));
  }
  public function index ($id = 0) {
    if ($id && ($store = Store::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $id, Store::IS_ENABLED))))) {
      if ($tags = array_merge (column_array ($store->tags, 'name'), Cfg::setting ('site', 'keywords')))
        foreach ($tags as $i => $tag)
          if (!$i) $this->add_meta (array ('property' => 'store:section', 'content' => $tag))->add_meta (array ('property' => 'store:tag', 'content' => $tag));
          else $this->add_meta (array ('property' => 'store:tag', 'content' => $tag));
           
      if ($also = $store->also ($this->tag))
        foreach ($also as $i => $a)
          $this->add_meta (array ('property' => 'og:see_also', 'content' => $a->content_page_url ($this->tag)));

      $this->set_title ($store->title . ' - ' . Cfg::setting ('site', 'title'))
           ->set_subtitle ($store->title)
           ->set_back_link (base_url ('stores'))
           ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_unique (array_merge ($store->keywords (), Cfg::setting ('site', 'keywords'))))))
           ->add_meta (array ('name' => 'description', 'content' => $store->mini_content (150)))
           ->add_meta (array ('property' => 'og:title', 'content' => $store->title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:description', 'content' => $store->mini_content (300)))
           ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $store->cover->url ('1200x630c'), 'alt' => $store->title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
           ->add_meta (array ('property' => 'store:modified_time', 'content' => $store->updated_at->format ('c')))
           ->add_meta (array ('property' => 'store:published_time', 'content' => $store->created_at->format ('c')))
           ->add_hidden (array ('id' => 'url', 'value' => base_url ($this->get_class (), 'show', $store->id)));
    } else {
      $this->set_title ('所有景點' . ' - ' . Cfg::setting ('site', 'title'))
           ->set_subtitle ('所有景點')
           ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_merge (array ('所有景點'), Cfg::setting ('site', 'keywords')))))
           ->add_meta (array ('property' => 'og:title', 'content' => '所有景點' . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'store:section', 'content' => '所有景點'));
    }

    Store::addConditions ($conditions, 'destroy_user_id IS NULL AND is_enabled = ?', Store::IS_ENABLED);
    $stores = Store::find ('all', array (
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    if ($tags = StoreTag::all (array ('select' => 'id', 'limit' => 5, 'conditions' => array ('is_on_site = ?', StoreTag::IS_ON_SITE_NAMES))))
      foreach ($tags as $tag)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('tag', $tag->id, 'stores')));
    
    $stores = json_encode (array_map (function ($i) {
      return array (
        'u' => base_url ($this->get_class (), 'show', $i->id),
        't' => $i->title,
        'i' => $i->icon_url (),
        'o' => $i->cover->url ('230x115c'),
        'a' => $i->latitude,
        'n' => $i->longitude);
    }, $stores));

    return $this->add_css (resource_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
                ->add_js (resource_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'my.js'))
                ->load_view (array (
                    'stores' => $stores,
                  ));
  }
  // public function index ($offset = 0) {
  //   $columns = array ();

  //   $configs = array ('stores', '%s');
  //   $conditions = conditions ($columns, $configs);
  //   Store::addConditions ($conditions, 'destroy_user_id IS NULL AND is_enabled = ?', Store::IS_ENABLED);

  //   $limit = 10;
  //   $total = Store::count (array ('conditions' => $conditions));
  //   $offset = $offset < $total ? $offset : 0;

  //   $this->load->library ('pagination');
  //   $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
  //   $stores = Store::find ('all', array (
  //       'offset' => $offset,
  //       'limit' => $limit,
  //       'order' => 'id DESC',
  //       'include' => array ('mappings'),
  //       'conditions' => $conditions
  //     ));

  //   if ($tags = StoreTag::all (array ('select' => 'id', 'limit' => 5, 'conditions' => array ('is_on_site = ?', StoreTag::IS_ON_SITE_NAMES))))
  //     foreach ($tags as $tag)
  //       $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('tag', $tag->id, 'stores')));

  //   return $this->set_title ('所有景點' . ' - ' . Cfg::setting ('site', 'title'))
  //               ->set_subtitle ('所有景點')
  //               ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_merge (array ('所有景點'), Cfg::setting ('site', 'keywords')))))
  //               ->add_meta (array ('property' => 'og:title', 'content' => '所有景點' . ' - ' . Cfg::setting ('site', 'title')))
  //               ->add_meta (array ('property' => 'store:section', 'content' => '所有景點'))
  //               ->add_js (Cfg::setting ('google', 'client_js_url'), false)
  //               ->load_view (array (
  //                   'stores' => $stores,
  //                   'pagination' => $pagination,
  //                   'columns' => $columns
  //                 ));
  // }
}
