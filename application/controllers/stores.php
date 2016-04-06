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
                ->add_hidden (array ('id' => 'id', 'value' => $store->id))
                ->load_view (array (
                    'store' => $store
                  ));
  }
  public function index ($id = 0) {
    Store::addConditions ($conditions, 'destroy_user_id IS NULL AND is_enabled = ?', Store::IS_ENABLED);
    $stores = Store::find ('all', array (
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    if ($id && ($store = Store::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $id, Store::IS_ENABLED))))) {
      if ($tags = array_merge (column_array ($store->tags, 'name'), Cfg::setting ('site', 'keywords')))
        foreach ($tags as $i => $tag)
          if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
          else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
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
           ->add_meta (array ('property' => 'article:modified_time', 'content' => $store->updated_at->format ('c')))
           ->add_meta (array ('property' => 'article:published_time', 'content' => $store->created_at->format ('c')))
           ->add_hidden (array ('id' => 'url', 'value' => base_url ($this->get_class (), 'show', $store->id)));
    } else {

    $title = '知名景點';
    $tag_names = column_array (StoreTag::all (array ('select' => 'name', 'limit' => 10)), 'name');
    if ($tags = array_unique (array_merge (array ($title), $tag_names, Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));

    if ($stores && ($store = $stores[rand(0, count ($stores) - 1)]))
      $this->add_meta (array ('property' => 'article:modified_time', 'content' => $store->updated_at->format ('c')))
           ->add_meta (array ('property' => 'article:published_time', 'content' => $store->created_at->format ('c')));

      $this->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
           ->set_subtitle ($title)
           ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
           ->add_meta (array ('name' => 'description', 'content' => implode (' ', array_merge (array (Cfg::setting ('site', 'title'), $title), column_array ($stores, 'title')))))
           ->add_meta (array ('property' => 'og:title', 'content' => $title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:description', 'content' => implode (' ', array_merge (array (Cfg::setting ('site', 'title'), $title), column_array ($stores, 'title')))))
      ;
    }


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
}
