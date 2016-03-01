<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Route::root ('main');
Route::get ('/tag', 'main');
Route::get ('/login', 'platform@login');
Route::get ('/platform/index', 'platform@login');
Route::get ('/platform', 'platform@login');
Route::get ('/march19', 'march19@dintao');

Route::resourcePagination_site (array (array ('dintaos', 'dintao')), 'dintaos');
Route::resourcePagination_site (array (array ('pictures', 'picture')), 'pictures');
Route::resourcePagination_site (array (array ('youtubes', 'youtube')), 'youtubes');

Route::resourcePagination_site (array ('tag', array ('dintaos', 'dintao')), 'tag_dintaos');
Route::resourcePagination_site (array ('tag', array ('pictures', 'picture')), 'tag_pictures');
Route::resourcePagination_site (array ('tag', array ('youtubes', 'youtube')), 'tag_youtubes');

Route::group ('admin', function () {
  Route::get ('/', 'main');
  Route::get ('/tag', 'main');

  Route::resourcePagination_is_on_site (array ('dintao-tags'), 'dintao_tags');
  Route::resourcePagination_is_on_site (array ('picture-tags'), 'picture_tags');
  Route::resourcePagination_is_on_site (array ('youtube-tags'), 'youtube_tags');
  Route::resourcePagination_is_on_site (array ('path-tags'), 'path_tags');

  Route::resourcePagination_is_enabled (array ('dintaos'), 'dintaos');
  Route::resourcePagination_is_enabled (array ('pictures'), 'pictures');
  Route::resourcePagination_is_enabled (array ('youtubes'), 'youtubes');
  Route::resourcePagination_is_enabled (array ('paths'), 'paths');

  Route::resourcePagination_is_enabled (array ('tag', 'dintaos'), 'tag_dintaos');
  Route::resourcePagination_is_enabled (array ('tag', 'pictures'), 'tag_pictures');
  Route::resourcePagination_is_enabled (array ('tag', 'youtubes'), 'tag_youtubes');
  
  Route::resourcePagination_is_enabled (array ('path', 'infos'), 'path_path_infos');
});
