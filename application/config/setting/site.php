<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$site['title'] = '北港迎媽祖';
$site['footer']['title'] = '北港迎媽祖 © 2016';
$site['footer']['description'] = '如有相關問題歡迎與<a href="https://www.facebook.com/comdan66" target="_blank">作者</a>討論。';
$site['keywords'] = array ('北港迎媽祖', '農曆三月十九', '北港', '朝天宮', '媽祖', '迎媽祖', '笨港', '農曆3月19', '319');
$site['description'] = '北港迎媽祖 © 2016';

$site['menu'] = array (
    '首頁' => array (
        '網站首頁' => array ('roles' => array ('all'), 'icon' => 'fi-h', 'href' => base_url (), 'class' => 'main', 'method' => 'index', 'target' => '_self'),
        '笨港文化' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('articles'), 'class' => 'articles', 'method' => '', 'target' => '_self'),
        'GPS定位' => array ('roles' => array ('all'), 'icon' => 'icon-gps_fixed', 'href' => base_url ('gps'), 'class' => 'gps', 'method' => '', 'target' => '_self'),
      ),
    '三月十九' => array (
        '北港廟會' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('march19'), 'class' => 'march19', 'method' => 'index', 'target' => '_self'),
        '媽祖定位' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => 'icon-gps_fixed', 'href' => base_url ('maps', 'gps'), 'class' => 'maps', 'method' => '', 'target' => '_self'),
        '路關簡介' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('march19', 'dintao'), 'class' => 'march19', 'method' => array ('dintao', 'iko'), 'target' => '_self'),
        '陣頭路線圖<span>(Google Maps)</span>' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('maps', 'dintao'), 'class' => 'maps', 'method' => 'dintao', 'target' => '_self'),
        '藝閣路線圖<span>(Google Maps)</span>' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('maps', 'iko'), 'class' => 'maps', 'method' => 'iko', 'target' => '_self'),
      ),
    '百年藝陣' => array (
        '所有陣頭' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('dintaos'), 'class' => 'dintaos', 'method' => '', 'target' => '_self'),
        '駕前陣頭' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('tag', 1, 'dintaos'), 'class' => 'tag_dintaos', 'method' => '', 'uri' => 1, 'target' => '_self'),
        '地方陣頭' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('tag', 2, 'dintaos'), 'class' => 'tag_dintaos', 'method' => '', 'uri' => 2, 'target' => '_self'),
        // '其他介紹' => array ('roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('tag', 3, 'dintaos'), 'class' => 'tag_dintaos', 'method' => '', 'uri' => 3, 'target' => '_self'),
      ),
    '相簿紀錄' => array (
        '所有照片' => array ('roles' => array ('all'), 'icon' => 'icon-images', 'href' => base_url ('pictures'), 'class' => 'pictures', 'method' => '', 'target' => '_self'),
        '三月十九' => array ('roles' => array ('all'), 'icon' => 'icon-images', 'href' => base_url ('tag', 1, 'pictures'), 'class' => 'tag_pictures', 'method' => '', 'uri' => 1, 'target' => '_self'),
      ),
    '影音欣賞' => array (
        '所有影片' => array ('roles' => array ('all'), 'icon' => 'icon-youtube', 'href' => base_url ('youtubes'), 'class' => 'youtubes', 'method' => '', 'target' => '_self'),
        '記錄北港' => array ('roles' => array ('all'), 'icon' => 'icon-youtube', 'href' => base_url ('tag', 1, 'youtubes'), 'class' => 'tag_youtubes', 'method' => '', 'uri' => 1, 'target' => '_self'),
        '三月十九' => array ('roles' => array ('all'), 'icon' => 'icon-youtube', 'href' => base_url ('tag', 2, 'youtubes'), 'class' => 'tag_youtubes', 'method' => '', 'uri' => 2, 'target' => '_self'),
      ),
    '景點區' => array (
        '所有景點' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('stores'), 'class' => 'stores', 'method' => '', 'target' => '_self'),
        '美食小吃' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('tag', 1, 'stores'), 'class' => 'tag_stores', 'method' => '', 'uri' => 1, 'target' => '_self'),
        '民宿旅館' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('tag', 2, 'stores'), 'class' => 'tag_stores', 'method' => '', 'uri' => 2, 'target' => '_self'),
        '名勝古蹟' => array ('roles' => array ('all'), 'icon' => 'icon-location', 'href' => base_url ('tag', 3, 'stores'), 'class' => 'tag_stores', 'method' => '', 'uri' => 3, 'target' => '_self'),
      ),
    '其他' => array (
        '開發作者' => array ('roles' => array ('all'), 'icon' => 'icon-user', 'href' => base_url ('others'), 'class' => 'others', 'method' => '', 'target' => '_self'),
        'PV' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('ajax', 'pv'), 'class' => 'ajax', 'method' => 'pv', 'target' => '_self'),
        '登入' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('platform', 'login'), 'class' => 'platform', 'method' => 'login', 'target' => '_self'),
        'ＦＢ登入' => array ('no_show' => true, 'roles' => array ('all'), 'icon' => 'icon-file-text2', 'href' => base_url ('platform', 'fb_sign_in'), 'class' => 'platform', 'method' => 'fb_sign_in', 'target' => '_self'),
      ),
  );