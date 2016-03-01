<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

// key 不准亂更改！
$role['role_names'] = array (
    'member' => '登入後台',
    'march19' => '編輯三月十九',
    'picture' => '管理相簿',
    'youtube' => '管理影音',
    'dintao' => '管理藝陣',
    'path' => '管理路徑',
    'root' => '最高權限',
  );

$role['roles'] = array_keys ($role['role_names']);
