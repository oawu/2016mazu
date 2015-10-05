<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$menu['admin'] = array (
    '權限' => array (
        '首頁' => array ('icon' => 'icon-home', 'href' => base_url ('admin'), 'class' => 'main', 'method' => 'index', 'target' => '_self', 'tags' => array ()),
        '角色設定' => array ('icon' => 'icon-user', 'href' => base_url ('admin', 'roles'), 'class' => 'roles', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '使用者設定' => array ('icon' => 'icon-user2', 'href' => base_url ('admin', 'users'), 'class' => 'users', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
    '文章系統' => array (
        '陣頭上搞' => array ('icon' => 'icon-file-text2', 'href' => base_url ('admin', 'dintaos'), 'class' => 'dintaos', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '美食上搞' => array ('icon' => 'icon-spoon-knife', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '照片上搞' => array ('icon' => 'icon-images', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
    '郵件系統' => array (
        '問題清單' => array ('icon' => 'icon-help', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '發送郵件' => array ('icon' => 'icon-mail', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
    '系統紀錄' => array (
        '排程紀錄' => array ('icon' => 'icon-clipboard', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '郵件紀錄' => array ('icon' => 'icon-paperplane', 'href' => base_url ('admin'), 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
  );

$menu['site'] = array (
    '朝天宮' => array (),
    '三月十九' => array (
        '陣頭路關' => array ('icon' => 'icon-github', 'href' => '', 'class' => 'main', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '藝閣路關' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '陣頭遶境地圖' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array (
            // '三月十九' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月二十' => array ('href' => '', 'class' => '', 'method' => ''),
          )),
        '藝閣遶境地圖' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array (
            // '三月十九' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月二十' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月廿一' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月廿二' => array ('href' => '', 'class' => '', 'method' => ''),
            // '三月廿三' => array ('href' => '', 'class' => '', 'method' => ''),
          )),
        '2015 回顧' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
    '百年藝陣' => array (
        '駕前陣頭' => array ('icon' => 'icon-github', 'href' => base_url ('dintaos', 'official'), 'class' => 'dintaos', 'method' => 'official', 'target' => '_self', 'tags' => array ()),
        '地方陣頭' => array ('icon' => 'icon-github', 'href' => base_url ('dintaos', 'local'), 'class' => 'dintaos', 'method' => 'local', 'target' => '_self', 'tags' => array ()),
        '其他介紹' => array ('icon' => 'icon-github', 'href' => base_url ('dintaos', 'other'), 'class' => 'dintaos', 'method' => 'other', 'target' => '_self', 'tags' => array ()),
      ),
    '文化交流' => array (
        '白沙屯 2015 路線' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
    '美食旅遊' => array (
        '美食地圖' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '在地小吃' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '名勝景點' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '交通資訊' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
    '吾鄉笨港' => array (
        '老照片說故事' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
        '耆老回憶' => array ('icon' => 'icon-github', 'href' => '', 'class' => '', 'method' => '', 'target' => '_self', 'tags' => array ()),
      ),
    '其他資源' => array (),
  );