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
         ->add_tab ('網站聲明', array ('href' => base_url ($this->get_class (), 'license'), 'index' => 3));

    if (!$this->other = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $this->get_method (), Other::IS_ENABLED))))
      return redirect_message (array (), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    $this->set_title ($this->other->title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($this->other->title)
         
         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_merge ($this->other->keywords (), Cfg::setting ('site', 'keywords')))))
         ->add_meta (array ('name' => 'description', 'content' => $this->other->mini_content (150)))
         ->add_meta (array ('property' => 'og:title', 'content' => $this->other->title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $this->other->mini_content (300)))
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $this->other->cover->url ('1200x630c'), 'alt' => $this->other->title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         ->add_meta (array ('property' => 'article:modified_time', 'content' => $this->other->updated_at->format ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => $this->other->created_at->format ('c')))
         
         ->add_css (resource_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
         ->add_js (resource_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'my.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $this->other->id))
         ->add_param ('other', $this->other);
  }
  public function author () {
    $prev = 'license';
    if ($prev = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $prev, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $prev->content_page_url ()));

    $next = 'developers';
    if ($next = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $next, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $next->content_page_url ()));

    $this->set_tab_index (1)
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
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
        // array (
        //     'title' => '文章編輯',
        //     'name' => '吳慧萱',
        //     'src' => resource_url ('resource', 'image', 'users', 'teresa.jpg'),
        //     'href' => 'https://www.facebook.com/teresa.chu.3348'
        //   )
      );




    $this->set_tab_index (2)
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
            'users' => $users,
          ));
  }
  public function license () {
    $prev = 'developers';
    if ($prev = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $prev, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $prev->content_page_url ()));

    $next = 'author';
    if ($next = Other::find ('one', array ('conditions' => array ('type = ? AND is_enabled = ? AND destroy_user_id IS NULL', $next, Other::IS_ENABLED))))
      $this->add_meta (array ('property' => 'og:see_also', 'content' => $next->content_page_url ()));

    $this->set_tab_index (3)
         ->load_view (array (
            'prev' => $prev,
            'next' => $next,
          ));
  }
}
