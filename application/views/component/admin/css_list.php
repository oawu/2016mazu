<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

if ($css_list) foreach ($css_list as $css) echo link_tag ($css) . (ENVIRONMENT !== 'production' ? "\n" : '');