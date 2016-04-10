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

    $json_ld = array (
        "@context" => "http://schema.org", "@type" => "Article",
        "mainEntityOfPage" => array (
            "@type" => "WebPage",
            "@id" => base_url (),
          ),
        "headline" => $title,
        "image" => array (
            "@type" => "ImageObject",
            "url" => $img = resource_url ('resource', 'image', 'og', 'larger2.jpg'),
            "height" => 630,
            "width" => 1200
          ),
        "datePublished" => date ('c'),
        "dateModified" => date ('c'),
        "author" => array (
            "@type" => "Person",
            "name" => '吳政賢',
            "url" => 'https://www.facebook.com/comdan66',
            "image" => array (
                "@type" => "ImageObject",
                "url" => resource_url ('resource', 'image', 'users', 'comdan66_300x300.jpg'),
                "height" => 300,
                "width" => 300
              )
          ),
        "publisher" => array (
            "@type" => "Organization",
            "name" => Cfg::setting ('site', 'title'),
            "logo" => array (
                "@type" => "ImageObject",
                "url" => resource_url ('resource', 'image', 'og', 'amp_logo_600x60.png'),
                "width" => 600,
                "height" => 60
              )
          ),
        "description" => $desc
      );

    if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    foreach (array ('articles', 'others', '', 'march19/dintao', 'march19/kio', 'maps/dintao', 'maps/iko', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
      $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

    $this->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)

         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
         ->add_meta (array ('name' => 'description', 'content' => $desc))
         ->add_meta (array ('property' => 'og:title', 'content' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $desc))
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img, 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         ->add_meta (array ('property' => 'article:modified_time', 'content' => date ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => date ('c')))
  
         ->add_meta (array ('property' => 'article:author', 'content' => 'https://www.facebook.com/comdan66'))
         ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'author', 'link')))

         ->load_view (array (
            'json_ld' => $json_ld,
            'prev' => $prev,
            'next' => $next,
          ), false, ENVIRONMENT == 'production' ? 60 : 0);
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


    $title = '陣頭路關';
    $desc = '清康熙卅三（公元一六九四年），福建湄洲朝天閣高僧樹璧奉請媽祖神尊來台，於農曆三月十九日午時登陸笨港（即今北港），神顯是永駐笨港，庇佑萬民，遂立祠奉祀，自此香火日盛。 地方信眾為感念媽祖聖德，例由笨港渡海回湄洲謁祖，回程在台南安平登陸，三月十九日鑾駕回抵笨港，同時舉行盛大慶典與繞境。嗣因甲午戰爭後，清廷日漸衰弱，列強環伺，台灣割讓日本，海疆日險，謁祖行程因而停止，惟地方信眾為紀念此一例行謁祖活動，仍於每年十九日、二十日迎請聖母舉行繞境，祈求風調雨順、國泰民安，此即本盛會之由來。 北港媽祖出巡繞境活動，經行政院文化部於民國九十九年六月十八日依據文化資產保存法第五十九條指定「北港朝天宮迎媽祖」為我國重要民俗。';

    if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    foreach (array ('articles', 'others', '', 'march19', 'march19/kio', 'maps/dintao', 'maps/iko', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
      $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

    $this->set_tab_index (1)
         ->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)

         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
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
          ), false, ENVIRONMENT == 'production' ? 60 : 0);
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

    $title = '藝閣路關';
    $desc = '農曆三月十九、二十日每日中午十二時０分，依編排號次以國樂車為首，停廟西側民主路口，其他藝閣依號次排列於後，中午十二時三十分進入遊行路線。晚間六時三十分依號次集合於廟西側民主路指定地點，晚間七時準時進入遊行路線。 農曆三月廿一、廿二、廿三日晚間六時三十分，依號次集合於廟西側民主路指定地點，晚間七時準時進入遊行路線，遊行完畢後解散。';

    if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    foreach (array ('articles', 'others', '', 'march19', 'march19/dintao', 'maps/dintao', 'maps/iko', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
      $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

    $this->set_tab_index (2)
         ->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)

         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
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
          ), false, ENVIRONMENT == 'production' ? 60 : 0);
  }
}
