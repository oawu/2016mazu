<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Route::root ('main');
Route::get ('/tag', 'main');
Route::get ('/login', 'platform@login');
Route::get ('/platform/index', 'platform@login');
Route::get ('/platform', 'platform@login');
// Route::get ('/march19', 'march19@in');
Route::get ('/others', 'others@author');

// Route::get ('/others/網站聲明', 'others@disclaimer');
// Route::get ('/others/製作人員', 'others@developers');

Route::resourcePagination_site (array (array ('articles', 'article')), 'articles');
Route::resourcePagination_site (array (array ('dintaos', 'dintao')), 'dintaos');
Route::resourcePagination_site (array (array ('pictures', 'picture')), 'pictures');
Route::resourcePagination_site (array (array ('youtubes', 'youtube')), 'youtubes');

Route::resourcePagination_site (array ('tag', array ('dintaos', 'dintao')), 'tag_dintaos');
Route::resourcePagination_site (array ('tag', array ('pictures', 'picture')), 'tag_pictures');
Route::resourcePagination_site (array ('tag', array ('youtubes', 'youtube')), 'tag_youtubes');

Route::site_store ();

Route::group ('admin', function () {
  Route::get ('/', 'main');
  Route::get ('/tag', 'main');

  Route::resourcePagination_is_on_site (array ('article-tags'), 'article_tags');
  Route::resourcePagination_is_on_site (array ('dintao-tags'), 'dintao_tags');
  Route::resourcePagination_is_on_site (array ('picture-tags'), 'picture_tags');
  Route::resourcePagination_is_on_site (array ('youtube-tags'), 'youtube_tags');
  Route::resourcePagination_is_on_site (array ('path-tags'), 'path_tags');
  Route::resourcePagination_is_on_site (array ('store-tags'), 'store_tags');

  Route::resourcePagination_is_enabled (array ('articles'), 'articles');
  Route::resourcePagination_is_enabled (array ('dintaos'), 'dintaos');
  Route::resourcePagination_is_enabled (array ('pictures'), 'pictures');
  Route::resourcePagination_is_enabled (array ('youtubes'), 'youtubes');
  Route::resourcePagination_is_enabled (array ('paths'), 'paths');
  Route::resourcePagination_is_enabled (array ('others'), 'others');
  Route::resourcePagination_is_enabled (array ('stores'), 'stores');

  Route::resourcePagination_is_enabled (array ('tag', 'articles'), 'tag_articles');
  Route::resourcePagination_is_enabled (array ('tag', 'dintaos'), 'tag_dintaos');
  Route::resourcePagination_is_enabled (array ('tag', 'pictures'), 'tag_pictures');
  Route::resourcePagination_is_enabled (array ('tag', 'youtubes'), 'tag_youtubes');
  
  Route::resourcePagination_is_enabled (array ('path', 'infos'), 'path_path_infos');
});

Route::group ('api', function () {
  Route::get ('/baishatun/1/', 'baishatun@com(0)');
  Route::get ('/baishatun/1/(:id)', 'baishatun@com($1)');

  Route::get ('/baishatun/2/', 'baishatun@showtaiwan1(0)');
  Route::get ('/baishatun/2/(:id)', 'baishatun@showtaiwan1($1)');

  Route::get ('/baishatun/3/', 'baishatun@showtaiwan2(0)');
  Route::get ('/baishatun/3/(:id)', 'baishatun@showtaiwan2($1)');
});