<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Tag_pictures extends Site_controller {
  private $tag = null;

  public function __construct () {
    parent::__construct ();

    if (!(($id = $this->uri->rsegments (3, 0)) && ($this->tag = PictureTag::find ('one', array ('conditions' => array ('id = ? AND is_on_site = ?', $id, PictureTag::IS_ON_SITE_NAMES))))))
      return redirect_message (array ('pictures'), array (
          '_flash_message' => '找不到該分類資料。'
        ));

    if (in_array ($this->uri->rsegments (2, 0), array ('edit', 'update', 'destroy', 'sort')))
      if (!(($id = $this->uri->rsegments (4, 0)) && ($this->picture = Picture::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL', $id))))))
        return redirect_message (array ('tag', $this->tag->id, 'pictures'), array (
            '_flash_message' => '找不到該筆資料。'
          ));

    $this->set_class ('pictures')
         ->add_param ('tag', $this->tag)
         ->add_param ('uri', $this->tag->id)
         ;
  }
  public function show ($tag_id, $id = 0) {
    if (!($id && ($picture = Picture::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $id, Picture::IS_ENABLED))))))
      return redirect_message (array ('tag', $this->tag->id, 'pictures'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if ($tags = array_merge (column_array ($picture->tags, 'name'), Cfg::setting ('site', 'keywords')))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    if ($also = $picture->also ($this->tag))
      foreach ($also as $i => $a)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => $a->content_page_url ($this->tag)));

    return $this->set_title ($picture->title . ' - ' . Cfg::setting ('site', 'title'))
                ->set_subtitle ($picture->title)
                ->set_back_link (base_url ('pictures'))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_merge ($picture->keywords (), Cfg::setting ('site', 'keywords')))))
                ->add_meta (array ('name' => 'description', 'content' => $picture->mini_content (150)))
                ->add_meta (array ('property' => 'og:title', 'content' => $picture->title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:description', 'content' => $picture->mini_content (300)))
                ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $picture->name->url ('1200x630c'), 'alt' => $picture->title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
                ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
                ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
                ->add_meta (array ('property' => 'article:modified_time', 'content' => $picture->updated_at->format ('c')))
                ->add_meta (array ('property' => 'article:published_time', 'content' => $picture->created_at->format ('c')))
                ->add_css (resource_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
                ->add_js (resource_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'my.js'))
                ->load_view (array (
                    'picture' => $picture,
                    'prev' => $picture->prev ($this->tag),
                    'next' => $picture->next ($this->tag),
                  ));
  }
  public function index ($tag_id, $offset = 0) {
    $columns = array ();

    $configs = array ('tag', $this->tag->id, 'pictures', '%s');
    $conditions = conditions ($columns, $configs);
    Picture::addConditions ($conditions, 'destroy_user_id IS NULL AND is_enabled = ?', Picture::IS_ENABLED);

    if ($picture_ids = column_array (PictureTagMapping::find ('all', array ('select' => 'picture_id', 'order' => 'picture_id DESC', 'conditions' => array ('picture_tag_id = ?', $this->tag->id))), 'picture_id'))
      Picture::addConditions ($conditions, 'id IN (?)', $picture_ids);
    else
      Picture::addConditions ($conditions, 'id = ?', -1);

    $limit = 24;
    $total = Picture::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $pictures = Picture::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    if ($tags = PictureTag::all (array ('select' => 'id', 'limit' => 5, 'conditions' => array ('id != ? AND is_on_site = ?', $this->tag->id, PictureTag::IS_ON_SITE_NAMES))))
      foreach ($tags as $tag)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('tag', $tag->id, 'pictures')));

    return $this->set_title ($this->tag->name . ' - ' . Cfg::setting ('site', 'title'))
                ->set_subtitle ($this->tag->name)
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_merge (array ($this->tag->name), Cfg::setting ('site', 'keywords')))))
                ->add_meta (array ('property' => 'og:title', 'content' => $this->tag->name . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'article:section', 'content' => $this->tag->name))
                ->add_css (resource_url ('resource', 'css', 'photoswipe_v4.1.0', 'my.css'))
                ->add_js (resource_url ('resource', 'javascript', 'photoswipe_v4.1.0', 'my.js'))
                ->load_view (array (
                    'pictures' => $pictures,
                    'pagination' => $pagination,
                    'columns' => $columns,
                    'has_photoswipe' => true
                  ));
  }
}
