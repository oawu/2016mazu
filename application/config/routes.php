<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
// $route['404_override'] = '';

// $route['default_controller'] = "main";
Route::root ('main');

// $route['admin'] = "admin/main";
Route::get ('admin', 'admin/main@index');

// ================================================================================================

Route::get ('admin/dintaos/(:num)/', 'admin/dintaos@index($1, 0)');
Route::get ('admin/dintaos/(:num)/(:num)', 'admin/dintaos@index($1, $2)');
Route::get ('admin/dintaos/(:num)/edit', 'admin/dintaos@edit($1)');
Route::post ('admin/dintaos/(:num)/update', 'admin/dintaos@update($1)');
Route::delete ('admin/dintaos/(:num)/destroy', 'admin/dintaos@destroy($1)');

Route::get ('dintao/(:num)', 'dintaos@content(all, $1)');
Route::get ('dintao/(:num)-(:any)', 'dintaos@content(all, $1)');
Route::get ('dintao/(:any)/(:num)', 'dintaos@content($1, $2)');
Route::get ('dintao/(:any)/(:num)-(:any)', 'dintaos@content($1, $2)');

foreach (array ( -1 => '所有陣頭', 1 => '駕前陣頭', 2 => '地方陣頭', 3 => '其他介紹') as $key => $text) {
  Route::get ('dintaos/' . $text . '', 'dintaos@index(' . $text . ', ' . $key . ', 0)');
  Route::get ('dintaos/' . $text . '/(:num)', 'dintaos@index(' . $text . ', ' . $key . ', $1)');
  Route::get ('dintaos/' . $text . '/(:num)/(:any)', 'dintaos@index(' . $text . ', ' . $key . ', $1, $2)');
  Route::get ('dintaos/' . $text . '/(:any)', 'dintaos@index(' . $text . ', ' . $key . ', 0, $1)');
}
Route::get ('dintaos/', 'dintaos@index(所有陣頭, -1, 0)');
Route::get ('dintaos/(:num)', 'dintaos@index(所有陣頭, -1, $1)');
Route::get ('dintaos/(:num)/(:any)', 'dintaos@index(所有陣頭, -1, $1, $2)');
Route::get ('dintaos/(:any)', 'dintaos@index(所有陣頭, -1, 0, $1)');

// ================================================================================================

Route::get ('admin/picture_tags/(:num)/', 'admin/picture_tags@index($1)');
Route::get ('admin/picture_tags/(:num)/edit', 'admin/picture_tags@edit($1)');
Route::post ('admin/picture_tags/(:num)/update', 'admin/picture_tags@update($1)');
Route::delete ('admin/picture_tags/(:num)/destroy', 'admin/picture_tags@destroy($1)');
Route::get ('admin/picture_tags/(:num)/pictures/', 'admin/picture_tags@pictures($1)');
Route::get ('admin/picture_tags/(:num)/pictures/(:num)', 'admin/picture_tags@pictures($1, $2)');
Route::get ('admin/picture_tags/(:num)/pictures/add', 'admin/picture_tags@add_pictures($1)');
Route::post ('admin/picture_tags/(:num)/pictures/create', 'admin/picture_tags@create_pictures($1)');
Route::get ('admin/picture_tags/(:num)/pictures/(:num)/edit', 'admin/picture_tags@edit_pictures($1, $2)');
Route::post ('admin/picture_tags/(:num)/pictures/(:num)/update', 'admin/picture_tags@update_pictures($1, $2)');
Route::delete ('admin/picture_tags/(:num)/pictures/(:num)/destroy', 'admin/picture_tags@destroy_pictures($1, $2)');
Route::post ('admin/picture_tags/(:num)/pictures/sort', 'admin/picture_tags@sort_pictures($1)');

Route::get ('admin/pictures/(:num)/', 'admin/pictures@index($1)');
Route::get ('admin/pictures/(:num)/edit', 'admin/pictures@edit($1)');
Route::post ('admin/pictures/(:num)/update', 'admin/pictures@update($1)');
Route::delete ('admin/pictures/(:num)/destroy', 'admin/pictures@destroy($1)');

Route::get ('picture/(:num)', 'pictures@content(all, $1)');
Route::get ('picture/(:num)-(:any)', 'pictures@content(all, $1)');
Route::get ('picture/(:any)/(:num)', 'pictures@content($1, $2)');
Route::get ('picture/(:any)/(:num)-(:any)', 'pictures@content($1, $2)');

Route::get ('pictures/', 'pictures@all(0)');
Route::get ('pictures/all/', 'pictures@all(0)');
Route::get ('pictures/all/(:num)', 'pictures@all($1)');
Route::get ('pictures/all/(:num)/(:any)', 'pictures@all($1, $2)');
Route::get ('pictures/all/(:any)', 'pictures@all(0, $1)');

Route::get ('pictures/北港舊照片/', 'pictures@index(北港舊照片, 0)');
Route::get ('pictures/北港舊照片/(:num)', 'pictures@index(北港舊照片, $1)');
Route::get ('pictures/2015三月十九/', 'pictures@index(2015三月十九, 0)');
Route::get ('pictures/2015三月十九/(:num)', 'pictures@index(2015三月十九, $1)');

// ================================================================================================

Route::get ('admin/youtube_tags/(:num)/', 'admin/youtube_tags@index($1)');
Route::get ('admin/youtube_tags/(:num)/edit', 'admin/youtube_tags@edit($1)');
Route::post ('admin/youtube_tags/(:num)/update', 'admin/youtube_tags@update($1)');
Route::delete ('admin/youtube_tags/(:num)/destroy', 'admin/youtube_tags@destroy($1)');
Route::get ('admin/youtube_tags/(:num)/youtubes/', 'admin/youtube_tags@youtubes($1)');
Route::get ('admin/youtube_tags/(:num)/youtubes/(:num)', 'admin/youtube_tags@youtubes($1, $2)');
Route::get ('admin/youtube_tags/(:num)/youtubes/add', 'admin/youtube_tags@add_youtubes($1)');
Route::post ('admin/youtube_tags/(:num)/youtubes/create', 'admin/youtube_tags@create_youtubes($1)');
Route::get ('admin/youtube_tags/(:num)/youtubes/(:num)/edit', 'admin/youtube_tags@edit_youtubes($1, $2)');
Route::post ('admin/youtube_tags/(:num)/youtubes/(:num)/update', 'admin/youtube_tags@update_youtubes($1, $2)');
Route::delete ('admin/youtube_tags/(:num)/youtubes/(:num)/destroy', 'admin/youtube_tags@destroy_youtubes($1, $2)');
Route::post ('admin/youtube_tags/(:num)/youtubes/sort', 'admin/youtube_tags@sort_youtubes($1)');

Route::get ('admin/youtubes/(:num)/', 'admin/youtubes@index($1)');
Route::get ('admin/youtubes/(:num)/edit', 'admin/youtubes@edit($1)');
Route::post ('admin/youtubes/(:num)/update', 'admin/youtubes@update($1)');
Route::delete ('admin/youtubes/(:num)/destroy', 'admin/youtubes@destroy($1)');

Route::get ('youtube/(:num)', 'youtubes@content(all, $1)');
Route::get ('youtube/(:num)-(:any)', 'youtubes@content(all, $1)');
Route::get ('youtube/(:any)/(:num)', 'youtubes@content($1, $2)');
Route::get ('youtube/(:any)/(:num)-(:any)', 'youtubes@content($1, $2)');

Route::get ('youtubes/', 'youtubes@all(0)');
Route::get ('youtubes/all/', 'youtubes@all(0)');
Route::get ('youtubes/all/(:num)', 'youtubes@all($1)');
Route::get ('youtubes/all/(:num)/(:any)', 'youtubes@all($1, $2)');
Route::get ('youtubes/all/(:any)', 'youtubes@all(0, $1)');

Route::get ('youtubes/紀錄片/', 'youtubes@index(紀錄片, 0)');
Route::get ('youtubes/紀錄片/(:num)', 'youtubes@index(紀錄片, $1)');

// ================================================================================================


Route::get ('admin/login', 'admin_login/main@login');
Route::post ('admin/singin', 'admin_login/main@singin');

// $route['main/index/(:num)/(:num)'] = "main/aaa/$1/$2";
// Route::get ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::post ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::put ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::delete ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::controller ('main', 'main');
  // whit get、post、put、delete prefix

/* End of file routes.php */
/* Location: ./application/config/routes.php */