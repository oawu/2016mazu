<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$admin['title'] = '後台管理 - 北港迎媽祖';
$admin['footer']['title'] = '北港迎媽祖 © 2016';
$admin['footer']['description'] = '如有相關問題歡迎與<a href="https://www.facebook.com/comdan66" target="_blank">作者</a>討論。';


$admin['menu'] = array (
    '店家管理' => array (
        '店家分類' => array ('roles' => array ('store'), 'icon' => 'icon-price-tag', 'href' => base_url ('admin', 'store-tags'), 'class' => 'store_tags', 'method' => '', 'target' => '_self'),
        '店家列表' => array ('roles' => array ('store'), 'icon' => 'icon-location', 'href' => base_url ('admin', 'stores'), 'class' => 'stores', 'method' => '', 'target' => '_self'),
      ),
    '路線管理' => array (
        '路線分類' => array ('roles' => array ('path'), 'icon' => 'icon-price-tag', 'href' => base_url ('admin', 'path-tags'), 'class' => 'path_tags', 'method' => '', 'target' => '_self'),
        '路線列表' => array ('roles' => array ('path'), 'icon' => 'icon-location', 'href' => base_url ('admin', 'paths'), 'class' => 'paths', 'method' => '', 'target' => '_self'),
      ),
    '文章管理' => array (
        '文章分類' => array ('roles' => array ('article'), 'icon' => 'icon-price-tag', 'href' => base_url ('admin', 'article-tags'), 'class' => 'article_tags', 'method' => '', 'target' => '_self'),
        '文章列表' => array ('roles' => array ('article'), 'icon' => 'icon-file-text2', 'href' => base_url ('admin', 'articles'), 'class' => 'articles', 'method' => '', 'target' => '_self'),
      ),
    '藝陣管理' => array (
        '藝陣分類' => array ('roles' => array ('dintao'), 'icon' => 'icon-price-tag', 'href' => base_url ('admin', 'dintao-tags'), 'class' => 'dintao_tags', 'method' => '', 'target' => '_self'),
        '藝陣列表' => array ('roles' => array ('dintao'), 'icon' => 'icon-file-text2', 'href' => base_url ('admin', 'dintaos'), 'class' => 'dintaos', 'method' => '', 'target' => '_self'),
      ),
    '相簿管理' => array (
        '相簿分類' => array ('roles' => array ('picture'), 'icon' => 'icon-price-tag', 'href' => base_url ('admin', 'picture-tags'), 'class' => 'picture_tags', 'method' => '', 'target' => '_self'),
        '相簿列表' => array ('roles' => array ('picture'), 'icon' => 'icon-images', 'href' => base_url ('admin', 'pictures'), 'class' => 'pictures', 'method' => '', 'target' => '_self'),
      ),
    '影音管理' => array (
        '影音分類' => array ('roles' => array ('youtube'), 'icon' => 'icon-price-tag', 'href' => base_url ('admin', 'youtube-tags'), 'class' => 'youtube_tags', 'method' => '', 'target' => '_self'),
        '影音列表' => array ('roles' => array ('youtube'), 'icon' => 'icon-youtube', 'href' => base_url ('admin', 'youtubes'), 'class' => 'youtubes', 'method' => '', 'target' => '_self'),
      ),
    '其他' => array (
        '工具' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => '', 'href' => base_url ('tools'), 'class' => 'tools', 'method' => '', 'target' => '_self'),
        '介紹列表' => array ('roles' => array ('other'), 'icon' => 'icon-file-text2', 'href' => base_url ('admin', 'others'), 'class' => 'others', 'method' => '', 'target' => '_self'),
        // '登入' => array ('no_show' => true, 'roles' => array (), 'icon' => 'icon-file-text2', 'href' => base_url ('platform', 'login'), 'class' => 'platform', 'method' => 'login', 'target' => '_self'),
        // 'ＦＢ登入' => array ('no_show' => true, 'roles' => array (), 'icon' => 'icon-file-text2', 'href' => base_url ('platform', 'fb_sign_in'), 'class' => 'platform', 'method' => 'fb_sign_in', 'target' => '_self'),
      ),
  );