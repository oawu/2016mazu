<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Tag_stores extends Site_controller {
  private $tag = null;

  public function __construct () {
    parent::__construct ();

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = StoreTag::find ('one', array ('conditions' => array ('id = ? AND is_on_site = ?', $id, StoreTag::IS_ON_SITE_NAMES))))))
      return redirect_message (array ('stores'), array (
          '_flash_message' => '找不到該分類資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->store = Store::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('tag', $this->tag->id, 'stores'), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->set_class ('stores')
         ->add_param ('tag', $this->tag)
         ->add_param ('uri', $this->tag->id)
         ;
  }

  public function index ($tag_id, $id = 0) {
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
           ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_merge ($store->keywords (), Cfg::setting ('site', 'keywords')))))
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
      $this->set_title ($this->tag->name . ' - ' . Cfg::setting ('site', 'title'))
           ->set_subtitle ($this->tag->name)
           ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_merge (array ($this->tag->name), Cfg::setting ('site', 'keywords')))))
           ->add_meta (array ('property' => 'og:title', 'content' => $this->tag->name . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'article:section', 'content' => $this->tag->name));
    }
    Store::addConditions ($conditions, 'destroy_user_id IS NULL AND is_enabled = ?', Store::IS_ENABLED);
    if ($store_ids = column_array (StoreTagMapping::find ('all', array ('select' => 'store_id', 'order' => 'store_id DESC', 'conditions' => array ('store_tag_id = ?', $this->tag->id))), 'store_id'))
      Store::addConditions ($conditions, 'id IN (?)', $store_ids);
    else
      Store::addConditions ($conditions, 'id = ?', -1);

    $limit = 10;
    $stores = Store::find ('all', array (
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));
    
    if ($tags = StoreTag::all (array ('select' => 'id', 'limit' => 5, 'conditions' => array ('id != ? AND is_on_site = ?', $this->tag->id, StoreTag::IS_ON_SITE_NAMES))))
      foreach ($tags as $tag)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('tag', $tag->id, 'stores')));

    $stores = json_encode (array_map (function ($i) {
      return array (
        'u' => base_url ('stores', 'show', $i->id),
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
