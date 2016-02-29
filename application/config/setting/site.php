<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$site['title'] = '北港迎媽祖';
$site['footer']['title'] = '北港迎媽祖 © 2016';
$site['footer']['description'] = '如有相關問題歡迎與<a href="https://www.facebook.com/comdan66" target="_blank">作者</a>討論。';
$site['keywords'] = array ('北港迎媽祖', '北港', '朝天宮', '媽祖', '迎媽祖', '笨港');
$site['description'] = '北港迎媽祖 © 2016';

$site['menu'] = array (
    '百年藝陣' => array (
        '所有陣頭' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('dintaos'), 'class' => 'dintaos', 'method' => '', 'target' => '_self'),
        '駕前陣頭' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('tag', 1, 'dintaos'), 'class' => 'tag_dintaos', 'method' => '', 'uri' => 1, 'target' => '_self'),
        '地方陣頭' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('tag', 2, 'dintaos'), 'class' => 'tag_dintaos', 'method' => '', 'uri' => 2, 'target' => '_self'),
        '其他介紹' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('tag', 3, 'dintaos'), 'class' => 'tag_dintaos', 'method' => '', 'uri' => 3, 'target' => '_self'),
      ),
    '相簿紀錄' => array (
        '所有照片' => array ('roles' => array ('all'), 'icon' => 'icon-images', 'href' => base_url ('pictures'), 'class' => 'pictures', 'method' => '', 'target' => '_self'),
        '三月十九' => array ('roles' => array ('all'), 'icon' => 'icon-images', 'href' => base_url ('tag', 1, 'pictures'), 'class' => 'tag_pictures', 'method' => '', 'uri' => 1, 'target' => '_self'),
        '笨港舊照片' => array ('roles' => array ('all'), 'icon' => 'icon-images', 'href' => base_url ('tag', 2, 'pictures'), 'class' => 'tag_pictures', 'method' => '', 'uri' => 2, 'target' => '_self'),
      ),
    '影音欣賞' => array (
        '所有影片' => array ('roles' => array ('all'), 'icon' => 'icon-youtube', 'href' => base_url ('youtubes'), 'class' => 'youtubes', 'method' => '', 'target' => '_self'),
        '記錄北港' => array ('roles' => array ('all'), 'icon' => 'icon-youtube', 'href' => base_url ('tag', 1, 'youtubes'), 'class' => 'tag_youtubes', 'method' => '', 'uri' => 1, 'target' => '_self'),
      ),
    '三月十九' => array (
        '路關簡介' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('invoice_tags'), 'class' => '', 'method' => '', 'target' => '_self'),
        '陣頭地圖' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('invoice_tags'), 'class' => '', 'method' => '', 'target' => '_self'),
        '藝閣地圖' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('invoice_tags'), 'class' => '', 'method' => '', 'target' => '_self'),
      ),
    '其他' => array (
        'PV' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('ajax', 'pv'), 'class' => 'ajax', 'method' => 'pv', 'target' => '_self'),
        '登入' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('platform', 'login'), 'class' => 'platform', 'method' => 'login', 'target' => '_self'),
        'ＦＢ登入' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('platform', 'fb_sign_in'), 'class' => 'platform', 'method' => 'fb_sign_in', 'target' => '_self'),
      ),
  );