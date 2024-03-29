<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Others extends Site_controller {
  private $other;

  public function __construct () {
    parent::__construct ();
  
    $this->add_tab ('網站作者', array ('href' => base_url ($this->get_class (), 'author'), 'index' => 1))
         ->add_tab ('製作人員', array ('href' => base_url ($this->get_class (), 'developers'), 'index' => 2))
         ->add_tab ('網站聲明', array ('href' => base_url ($this->get_class (), 'license'), 'index' => 3))
         ->add_tab ('資源引用', array ('href' => base_url ($this->get_class (), 'resources'), 'index' => 5));

    if (!$this->other = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $this->get_method (), Other::IS_ENABLED))))
      return redirect_message (array (), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $other = $this->other;
    $json_ld = array (
        "@context" => "http://schema.org", "@type" => "Article",
        "mainEntityOfPage" => array (
            "@type" => "WebPage",
            "@id" => base_url ('others'),
          ),
        "headline" => $other->title,
        "image" => array (
            "@type" => "ImageObject",
            "url" => $other->cover->url ('1200x630c'),
            "height" => 630,
            "width" => 1200
          ),
        "datePublished" => $other->created_at->format ('c'),
        "dateModified" => $other->updated_at->format ('c'),
        "author" => array (
            "@type" => "Person",
            "name" => $other->user->name,
            "url" => $other->user->facebook_link (),
            "image" => array (
                "@type" => "ImageObject",
                "url" => $other->user->avatar (300, 300),
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
        "description" => $other->mini_content (150)
      );


    $this->set_title ($this->other->title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($this->other->title)
         
         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_unique (array_merge ($this->other->keywords (), Cfg::setting ('site', 'keywords'))))))
         ->add_meta (array ('name' => 'description', 'content' => $this->other->mini_content (150)))
         ->add_meta (array ('property' => 'og:title', 'content' => $this->other->title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $this->other->mini_content (300)))
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $this->other->cover->url ('1200x630c'), 'alt' => $this->other->title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         ->add_meta (array ('property' => 'article:modified_time', 'content' => $this->other->updated_at->format ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => $this->other->created_at->format ('c')))
 
         ->add_meta (array ('property' => 'article:author', 'content' => $other->user->facebook_link ()))
         ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'author', 'link')))

         ->add_css (resource_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
         ->add_js (resource_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'my.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $this->other->id))
         ->add_param ('other', $this->other)
         ->add_param ('json_ld', $json_ld);
  }
  public function author () {
    $prev = 'resources';
    if ($prev = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $prev, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $prev->content_page_url ()));

    $next = 'developers';
    if ($next = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $next, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $next->content_page_url ()));

    $this->set_tab_index (1)
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ), false, ENVIRONMENT == 'production' ? 60 * 3 : 0);
  }
  public function developers () {
    $prev = 'author';
    if ($prev = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $prev, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $prev->content_page_url ()));

    $next = 'license';
    if ($next = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $next, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $next->content_page_url ()));

    $users = array (
        array (
            'title' => '網站作者',
            'name' => '吳政賢',
            'src' => resource_url ('resource', 'image', 'users', 'comdan66.jpg'),
            'href' => 'https://www.facebook.com/comdan66'
          ),
        array (
            'title' => '視覺顧問',
            'name' => '朱慧華',
            'src' => resource_url ('resource', 'image', 'users', 'teresa.jpg'),
            'href' => 'https://www.facebook.com/teresa.chu.3348'
          ),
        array (
            'title' => '文章編輯',
            'name' => '吳慧萱',
            'src' => resource_url ('resource', 'image', 'users', '100000834456708.jpg'),
            'href' => 'https://www.facebook.com/profile.php?id=100000834456708'
          )
      );

    $this->set_tab_index (2)
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
            'users' => $users,
          ), false, ENVIRONMENT == 'production' ? 60 * 3 : 0);
  }
  public function license () {
    $prev = 'developers';
    if ($prev = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $prev, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $prev->content_page_url ()));

    $next = 'resources';
    if ($next = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $next, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $next->content_page_url ()));

    $this->set_tab_index (3)
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ), false, ENVIRONMENT == 'production' ? 60 * 3 : 0);
  }
  public function resources () {
    $prev = 'license';
    if ($prev = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $prev, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $prev->content_page_url ()));

    $next = 'author';
    if ($next = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $next, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $next->content_page_url ()));

    $this->set_tab_index (5)
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ), false, ENVIRONMENT == 'production' ? 60 * 3 : 0);
  }
}
