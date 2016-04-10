<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Articles extends Site_controller {
  private $tag = null;

  public function __construct () {
    parent::__construct ();

    $this->add_param ('tag', $this->tag)
         ;
  }
  public function show ($id = 0) {
    if (!($id && ($article = Article::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $id, Article::IS_ENABLED))))))
      return redirect_message (array ('articles'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    if ($tags = array_unique (array_merge (column_array ($article->tags, 'name'), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    if ($also = $article->also ())
      foreach ($also as $i => $a)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => $a->content_page_url ($this->tag)));

    $json_ld = array (
      "@context" => "http://schema.org", "@type" => "Article",
      "mainEntityOfPage" => array (
          "@type" => "WebPage",
          "@id" => base_url ('articles'),
        ),
      "headline" => $article->title,
      "image" => array (
          "@type" => "ImageObject",
          "url" => $article->cover->url ('1200x630c'),
          "height" => 630,
          "width" => 1200
        ),
      "datePublished" => $article->created_at->format ('c'),
      "dateModified" => $article->updated_at->format ('c'),
      "author" => array (
          "@type" => "Person",
          "name" => $article->user->name,
          "url" => $article->user->facebook_link (),
          "image" => array (
              "@type" => "ImageObject",
              "url" => $article->user->avatar (300, 300),
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
      "description" => $article->mini_content (150)
      );

    return $this->set_title ($article->title . ' - ' . Cfg::setting ('site', 'title'))
                ->set_subtitle ($article->title)
                ->set_back_link (base_url ('articles'))
                ->add_css (resource_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
                ->add_js (resource_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'my.js'))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', array_unique (array_merge ($article->keywords (), Cfg::setting ('site', 'keywords'))))))
                ->add_meta (array ('name' => 'description', 'content' => $article->mini_content (150)))
                ->add_meta (array ('property' => 'og:title', 'content' => $article->title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:description', 'content' => $article->mini_content (300)))
                ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $article->cover->url ('1200x630c'), 'alt' => $article->title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
                ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
                ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
                ->add_meta (array ('property' => 'article:modified_time', 'content' => $article->updated_at->format ('c')))
                ->add_meta (array ('property' => 'article:published_time', 'content' => $article->created_at->format ('c')))

                ->add_meta (array ('property' => 'article:author', 'content' => $article->user->facebook_link ()))
                ->add_meta (array ('property' => 'article:publisher', 'content' => Cfg::setting ('facebook', 'author', 'link')))

                ->add_hidden (array ('id' => 'id', 'value' => $article->id))
                ->load_view (array (
                    'json_ld' => $json_ld,
                    'article' => $article,
                    'prev' => $article->prev ($this->tag),
                    'next' => $article->next ($this->tag),
                  ), false, ENVIRONMENT == 'production' ? 60 : 0);
  }
  public function index ($offset = 0) {
    $columns = array ();

    $configs = array ('articles', '%s');
    $conditions = conditions ($columns, $configs);
    Article::addConditions ($conditions, 'destroy_user_id IS NULL AND is_enabled = ?', Article::IS_ENABLED);

    $limit = 10;
    $total = Article::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $pagination = $this->pagination->initialize (array_merge (array ('total_rows' => $total, 'num_links' => 3, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li class="f">', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li class="p">', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li class="n">', 'next_tag_close' => '</li>', 'last_tag_open' => '<li class="l">', 'last_tag_close' => '</li>'), $configs))->create_links ();
    $articles = Article::find ('all', array (
        'offset' => $offset,
        'limit' => $limit,
        'order' => 'id DESC',
        'include' => array ('mappings'),
        'conditions' => $conditions
      ));

    if ($tags = ArticleTag::all (array ('select' => 'id, name', 'limit' => 5, 'conditions' => array ('is_on_site = ?', ArticleTag::IS_ON_SITE_NAMES))))
      foreach ($tags as $tag)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ('tag', $tag->id, 'articles')));

    $title = '笨港文化';
    $tag_names = column_array (ArticleTag::all (array ('select' => 'name', 'limit' => 10)), 'name');
    if ($tags = array_unique (array_merge (array ($title), $tag_names, Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));

    if ($articles && ($article = $articles[rand(0, count ($articles) - 1)]))
      $this->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = $article->cover->url ('1200x630c'), 'alt' => $article->title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
           ->add_meta (array ('property' => 'article:modified_time', 'content' => $article->updated_at->format ('c')))
           ->add_meta (array ('property' => 'article:published_time', 'content' => $article->created_at->format ('c')));

    return $this->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
                ->set_subtitle ($title)
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
                ->add_meta (array ('name' => 'description', 'content' => implode (' ', array_merge (array ($title), column_array ($articles, 'title')))))
                ->add_meta (array ('property' => 'og:title', 'content' => $title . ' - ' . Cfg::setting ('site', 'title')))
                ->add_meta (array ('property' => 'og:description', 'content' => implode (' ', array_merge (array ($title), column_array ($articles, 'title')))))
                ->load_view (array (
                    'articles' => $articles,
                    'pagination' => $pagination,
                    'columns' => $columns
                  ), false, ENVIRONMENT == 'production' ? 60 : 0);
  }
}
