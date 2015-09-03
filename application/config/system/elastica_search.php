<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$elastica_search['is_enabled'] = true;
$elastica_search['ip']     = 'localhost';
$elastica_search['port']   = '9200';
$elastica_search['index']  = 'oatest';
$elastica_search['create_limit']  = 1000;
$elastica_search['class_directory']  = array ('application', 'searches');
$elastica_search['class_suffix']  = 'Search';
