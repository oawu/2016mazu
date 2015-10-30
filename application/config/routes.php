<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Route::root ('main');

Route::group ('admin', function () {
  Route::get ('/', 'main');

  Route::resourcePagination (array ('dintao_tags'), 'dintao_tags');
  Route::resourcePagination (array ('picture_tags'), 'picture_tags');
  Route::resourcePagination (array ('youtube_tags'), 'youtube_tags');
  Route::resourcePagination (array ('dintaos'), 'dintaos');
  Route::resourcePagination (array ('pictures'), 'pictures');
  Route::resourcePagination (array ('youtubes'), 'youtubes');
  Route::resourcePagination (array ('dintao_tags', 'dintaos'), 'dintao_tag_dintaos');
  Route::resourcePagination (array ('picture_tags', 'pictures'), 'picture_tag_pictures');
  Route::resourcePagination (array ('youtube_tags', 'youtubes'), 'youtube_tag_youtubes');
});

$site = array (
  'dintao' => array ('all' => '所有陣頭', 'keywords' => array ('駕前陣頭', '地方陣頭', '其他介紹')),
  'picture' => array ('all' => '所有照片', 'keywords' => array ('北港舊照片', '2015三月十九')),
  'youtube' => array ('all' => '所有影片', 'keywords' => array ('紀錄片')),
  );

foreach ($site as $key => $values) {
  foreach ($values['keywords'] as $keyword) {
    Route::get ($key . 's/' . $keyword . '/', $key . 's@index(' . $keyword . ', 0)');
    Route::get ($key . 's/' . $keyword . '/(:num)', $key . 's@index(' . $keyword . ', $1)');
  }
  Route::get ($key . 's/', $key . 's@all(0)');
  Route::get ($key . 's/(:num)', $key . 's@all($1)');
  Route::get ($key . 's/' . $values['all'] . '/', $key . 's@all(0)');
  Route::get ($key . 's/' . $values['all'] . '/(:num)', $key . 's@all($1)');

  // Search on
  Route::get ($key . 's/(:any)/', $key . 's@all(0, $1)');
  Route::get ($key . 's/(:any)/(:num)', $key . 's@all($2, $1)');

  Route::get ($key . '/(:num)', $key . 's@content(all, $1)');
  Route::get ($key . '/(:num)-(:any)', $key . 's@content(' . $values['all'] . ', $1)');
  Route::get ($key . '/(:any)/(:num)', $key . 's@content($1, $2)');
  Route::get ($key . '/(:any)/(:num)-(:any)', $key . 's@content($1, $2)');
}
