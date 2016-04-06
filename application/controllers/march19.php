<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class March19 extends Site_controller {
  
  public function __construct () {
    parent::__construct ();
    
    if (in_array ($this->uri->rsegments (2, 0), array ('dintao', 'iko')))
      $this->add_tab ('陣頭路關', array ('href' => base_url ($this->get_class (), 'dintao'), 'index' => 1))
           ->add_tab ('藝閣路關', array ('href' => base_url ($this->get_class (), 'iko'), 'index' => 2));
  }
  public function index () {
    $prev = null;
    $next = array (
        'url' => base_url ($this->get_class (), 'dintao'),
        'title' => '陣頭路關'
      );



    $title = '北港廟會';
    $desc = '農曆三月期間在臺灣各地迎媽祖的廟會活動非常頻繁，而在這段時間的北港鎮更能看到媽祖廟會的盛況非常，它對北港人的意義更是第二個過年一般，多數在外地工作的北港遊子都會回鄉參與！';

    if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    foreach (array ('articles', 'others', '', 'maps/dintao', 'maps/iko', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
      $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

    $this->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)


         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_unique (array_merge (array ('北港廟會'), Cfg::setting ('site', 'keywords'))))))
         ->add_meta (array ('name' => 'description', 'content' => $desc))
         ->add_meta (array ('property' => 'og:title', 'content' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $desc))
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = resource_url ('resource', 'image', 'og', 'larger2.jpg'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         ->add_meta (array ('property' => 'article:modified_time', 'content' => date ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => date ('c')))
        
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
  }
  public function dintao () {
    $prev = array (
        'url' => base_url ($this->get_class ()),
        'title' => '北港廟會'
      );
    $next = array (
        'url' => base_url ($this->get_class (), 'iko'),
        'title' => '藝閣路關'
      );
    $this->set_tab_index (1)
         ->set_subtitle ('陣頭路關')
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
  }
  public function iko () {
    $prev = array (
        'url' => base_url ($this->get_class (), 'dintao'),
        'title' => '陣頭路關'
      );
    $next = array (
        'url' => base_url ('maps', 'dintao'),
        'title' => '陣頭地圖'
      );
    $this->set_tab_index (2)
         ->set_subtitle ('藝閣路關')
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
  }
}
