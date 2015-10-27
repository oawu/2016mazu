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












// Route::post ('pics/(:num)/com/(:num)', 'main@picx($1, $2)');
// Route::resource ('admin/pics', 'admin/pics');
// Route::resource (array ('pics', 'comments'), 'pic_comments', 'admin');
// Route::resource (array ('pics', 'comments', 'xx'), 'pic_comments');

// Route::resource (array ('dintao_tags'), 'dintao_tags');
// Route::resource (array ('picture_tags'), 'picture_tags');
// Route::resource (array ('youtube_tags'), 'youtube_tags');

// Route::resource (array ('pictures', 'comments'), 'youtube_comments');
// Route::resource (array ('pictures'), 'youtube_comments');
// Route::resourcePagination (array ('pictures'), 'youtube_comments');

// echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
// var_dump (Route::getRoute ());
// exit ();
// Route::group ('admin', array (
//     'restful' => ''
//   ));

// $route['admin'] = "admin/main";
// Route::get ('admin', 'admin/main@index');

// // ================================================================================================

// function nested_restful ($names, $count = 1) {
//   $name = array_shift ($names);
//   if ($names)
//     $returns = nested_restful ($names, $count + 1);
//   else
//     $returns = array ();

//   $routes = array (
//     array ('m' => 'get',    'p' => $name . '/'               , 'f' => array ('index'),   'v' => array ()),
//     array ('m' => 'get',    'p' => $name . '/(:num)/'        , 'f' => array ('index'),   'v' => array ('$' . $count)),
//     array ('m' => 'get',    'p' => $name . '/add/'           , 'f' => array ('add'),     'v' => array ()),
//     array ('m' => 'post',   'p' => $name . '/create/'        , 'f' => array ('create'),  'v' => array ()),
//     array ('m' => 'get',    'p' => $name . '/(:num)/edit/'   , 'f' => array ('edit'),    'v' => array ('$' . $count)),
//     array ('m' => 'post',   'p' => $name . '/(:num)/update/' , 'f' => array ('update'),  'v' => array ('$' . $count)),
//     array ('m' => 'delete', 'p' => $name . '/(:num)/destroy/', 'f' => array ('destroy'), 'v' => array ('$' . $count)),
//     array ('m' => 'post',   'p' => $name . '/sort/'          , 'f' => array ('sort'),    'v' => array ()),
//   );
  
//   foreach ($returns as $i => &$return) {
//     $return['p'] = $name . '/(:num)/' . $return['p'];
//     if ($names)
//       array_unshift ($return['f'], $names[0]);
//     array_unshift ($return['v'], '$' . $count);
//   }

//   return array_merge ($routes, $returns);
// }
// function restful_generator ($nested_restfuls, $b = '') {
//   foreach ($nested_restfuls as $nested_restful)
//     foreach (nested_restful ($nested_restful = is_string ($nested_restful) ? array ($nested_restful) : $nested_restful) as $restful)
//       Route::$restful['m'] ($b . $restful['p'], $b . $nested_restful[0] . '@' . implode ('_', $restful['f']) . '(' . implode ($restful['v'], ', ') .')');
// }
// $nested_restfuls = array (
//     array ('dintao_tags', 'dintaos'),
//     array ('picture_tags', 'pictures'),
//     array ('youtube_tags', 'youtubes'),
//     array ('dintaos'),
//     array ('pictures'),
//     array ('youtubes')
// );

// restful_generator ($nested_restfuls, 'admin/');

// Route::get ('dintao/(:num)', 'dintaos@content(all, $1)');
// Route::get ('dintao/(:num)-(:any)', 'dintaos@content(all, $1)');
// Route::get ('dintao/(:any)/(:num)', 'dintaos@content($1, $2)');
// Route::get ('dintao/(:any)/(:num)-(:any)', 'dintaos@content($1, $2)');

// Route::get ('dintaos/', 'dintaos@all(0)');
// Route::get ('dintaos/all/', 'dintaos@all(0)');
// Route::get ('dintaos/all/(:num)', 'dintaos@all($1)');
// Route::get ('dintaos/all/(:num)/(:any)', 'dintaos@all($1, $2)');
// Route::get ('dintaos/all/(:any)', 'dintaos@all(0, $1)');

// Route::get ('dintaos/駕前陣頭/', 'dintaos@index(駕前陣頭, 0)');
// Route::get ('dintaos/駕前陣頭/(:num)', 'dintaos@index(駕前陣頭, $1)');
// Route::get ('dintaos/地方陣頭/', 'dintaos@index(地方陣頭, 0)');
// Route::get ('dintaos/地方陣頭/(:num)', 'dintaos@index(地方陣頭, $1)');
// Route::get ('dintaos/其他介紹/', 'dintaos@index(其他介紹, 0)');
// Route::get ('dintaos/其他介紹/(:num)', 'dintaos@index(其他介紹, $1)');

// // ================================================================================================

// Route::get ('picture/(:num)', 'pictures@content(all, $1)');
// Route::get ('picture/(:num)-(:any)', 'pictures@content(all, $1)');
// Route::get ('picture/(:any)/(:num)', 'pictures@content($1, $2)');
// Route::get ('picture/(:any)/(:num)-(:any)', 'pictures@content($1, $2)');

// Route::get ('pictures/', 'pictures@all(0)');
// Route::get ('pictures/all/', 'pictures@all(0)');
// Route::get ('pictures/all/(:num)', 'pictures@all($1)');
// Route::get ('pictures/all/(:num)/(:any)', 'pictures@all($1, $2)');
// Route::get ('pictures/all/(:any)', 'pictures@all(0, $1)');

// Route::get ('pictures/北港舊照片/', 'pictures@index(北港舊照片, 0)');
// Route::get ('pictures/北港舊照片/(:num)', 'pictures@index(北港舊照片, $1)');
// Route::get ('pictures/2015三月十九/', 'pictures@index(2015三月十九, 0)');
// Route::get ('pictures/2015三月十九/(:num)', 'pictures@index(2015三月十九, $1)');

// // ================================================================================================

// Route::get ('youtube/(:num)', 'youtubes@content(all, $1)');
// Route::get ('youtube/(:num)-(:any)', 'youtubes@content(all, $1)');
// Route::get ('youtube/(:any)/(:num)', 'youtubes@content($1, $2)');
// Route::get ('youtube/(:any)/(:num)-(:any)', 'youtubes@content($1, $2)');

// Route::get ('youtubes/', 'youtubes@all(0)');
// Route::get ('youtubes/all/', 'youtubes@all(0)');
// Route::get ('youtubes/all/(:num)', 'youtubes@all($1)');
// Route::get ('youtubes/all/(:num)/(:any)', 'youtubes@all($1, $2)');
// Route::get ('youtubes/all/(:any)', 'youtubes@all(0, $1)');

// Route::get ('youtubes/紀錄片/', 'youtubes@index(紀錄片, 0)');
// Route::get ('youtubes/紀錄片/(:num)', 'youtubes@index(紀錄片, $1)');

// // ================================================================================================


// Route::get ('admin/login', 'admin_login/main@login');
// Route::post ('admin/singin', 'admin_login/main@singin');

// // $route['main/index/(:num)/(:num)'] = "main/aaa/$1/$2";
// // Route::get ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// // Route::post ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// // Route::put ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// // Route::delete ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// // Route::controller ('main', 'main');
//   // whit get、post、put、delete prefix

// /* End of file routes.php */
// /* Location: ./application/config/routes.php */