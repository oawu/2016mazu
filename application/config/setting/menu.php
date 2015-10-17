<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$menu['admin'] = array (
    '權限' => array (
        '首頁' => array ('icon' => 'icon-home', 'href' => base_url ('admin'), 'class' => 'main', 'method' => 'index', 'target' => '_self'),
        '角色設定' => array ('icon' => 'icon-user', 'href' => base_url ('admin', 'roles'), 'class' => 'roles', 'method' => '', 'target' => '_self'),
        '使用者設定' => array ('icon' => 'icon-user2', 'href' => base_url ('admin', 'users'), 'class' => 'users', 'method' => '', 'target' => '_self'),
      ),
    '照片系統' => array (
        '照片標籤' => array ('icon' => 'icon-price-tags', 'href' => base_url ('admin', 'picture_tags'), 'class' => 'picture_tags', 'method' => '', 'target' => '_self'),
        '照片管理' => array ('icon' => 'icon-images', 'href' => base_url ('admin', 'pictures'), 'class' => 'pictures', 'method' => '', 'target' => '_self'),
      ),
    '影片系統' => array (
        '影片標籤' => array ('icon' => 'icon-tags', 'href' => base_url ('admin', 'youtube_tags'), 'class' => 'youtube_tags', 'method' => '', 'target' => '_self'),
        '影片管理' => array ('icon' => 'icon-youtube', 'href' => base_url ('admin', 'youtubes'), 'class' => 'youtubes', 'method' => '', 'target' => '_self'),
      ),
    '文章系統' => array (
        '陣頭上搞' => array ('icon' => 'icon-file-text2', 'href' => base_url ('admin', 'dintaos'), 'class' => 'dintaos', 'method' => '', 'target' => '_self'),
        '美食上搞' => array ('icon' => 'icon-spoon-knife', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
      ),
    '郵件系統' => array (
        '問題清單' => array ('icon' => 'icon-help', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
        '發送郵件' => array ('icon' => 'icon-mail', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
      ),
    '系統紀錄' => array (
        '排程紀錄' => array ('icon' => 'icon-clipboard', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
        '郵件紀錄' => array ('icon' => 'icon-paperplane', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self'),
      ),
  );

$menu['site'] = array (
    '朝天宮' => array (
        '各殿簡介' => array ('icon' => 'icon-github', 'href' => '', 'class' => 'palaces', 'method' => '', 'target' => '_self'),
        '建築之美' => array ('icon' => 'icon-github', 'href' => '', 'class' => 'builds', 'method' => '', 'target' => '_self'),
      ),
    '相簿紀錄' => array (
        '所有照片' => array ('icon' => 'icon-github', 'href' => base_url ('pictures', 'all'), 'class' => 'pictures', 'method' => 'all', 'target' => '_self'),
        '三月十九' => array ('icon' => 'icon-github', 'href' => base_url ('pictures', '2015三月十九'), 'class' => 'pictures', 'method' => '2015三月十九', 'target' => '_self'),
        '笨港舊照片' => array ('icon' => 'icon-github', 'href' => base_url ('pictures', '北港舊照片'), 'class' => 'pictures', 'method' => '北港舊照片', 'target' => '_self'),
      ),
    '影音欣賞' => array (
        '所有影片' => array ('icon' => 'icon-github', 'href' => base_url ('youtubes', 'all'), 'class' => 'youtubes', 'method' => 'all', 'target' => '_self'),
        '記錄北港' => array ('icon' => 'icon-github', 'href' => base_url ('youtubes', '紀錄片'), 'class' => 'youtubes', 'method' => '紀錄片', 'target' => '_self'),
      ),
    '百年藝陣' => array (
        '所有陣頭' => array ('icon' => 'icon-github', 'href' => base_url ('dintaos', '所有陣頭'), 'class' => 'dintaos', 'method' => '所有陣頭', 'target' => '_self'),
        '駕前陣頭' => array ('icon' => 'icon-github', 'href' => base_url ('dintaos', '駕前陣頭'), 'class' => 'dintaos', 'method' => '駕前陣頭', 'target' => '_self'),
        '地方陣頭' => array ('icon' => 'icon-github', 'href' => base_url ('dintaos', '地方陣頭'), 'class' => 'dintaos', 'method' => '地方陣頭', 'target' => '_self'),
        '其他介紹' => array ('icon' => 'icon-github', 'href' => base_url ('dintaos', '其他介紹'), 'class' => 'dintaos', 'method' => '其他介紹', 'target' => '_self'),
      ),
    '三月十九' => array (
        '路關' => array ('icon' => 'icon-github', 'href' => '', 'class' => 'paths', 'method' => '', 'target' => '_self'),
        '陣頭遶境地圖' => array ('icon' => 'icon-github', 'href' => '', 'class' => 'paths', 'method' => '', 'target' => '_self', 'tags' => array (
            // '三月十九' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月二十' => array ('href' => '', 'class' => '', 'method' => ''),
          )),
        '藝閣遶境地圖' => array ('icon' => 'icon-github', 'href' => '', 'class' => 'paths', 'method' => '', 'target' => '_self', 'tags' => array (
            // '三月十九' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月二十' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月廿一' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月廿二' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月廿三' => array ('href' => '', 'class' => '', 'method' => ''),
          )),
        // '2015 回顧' => array ('icon' => 'icon-github', 'href' => '', 'class' => 'paths', 'method' => '', 'target' => '_self'),
      ),
    '文化交流' => array (
        '白沙屯 2015 路線' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self'),
      ),
    '美食旅遊' => array (
        '美食地圖' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self'),
        '在地小吃' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self'),
        '名勝景點' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self'),
        '交通資訊' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self'),
      ),
    // '吾鄉笨港' => array (
    //     '老照片說故事' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self'),
    //     '耆老回憶' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self'),
    //   ),
    '其他資源' => array (),
  );