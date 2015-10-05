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

Route::get ('admin/dintaos/(:num)/', 'admin/dintaos@index($1, 0)');
Route::get ('admin/dintaos/(:num)/(:num)', 'admin/dintaos@index($1, $2)');

Route::get ('dintao/(:num)', 'dintaos@content($1)');
Route::get ('dintao/(:num)-(:any)', 'dintaos@content($1)');

Route::get ('admin/picture_tags/(:num)/', 'admin/picture_tags@index($1)');
Route::get ('admin/picture_tags/(:num)/pictures/', 'admin/picture_tags@pictures($1)');
Route::get ('admin/picture_tags/(:num)/pictures/(:num)', 'admin/picture_tags@pictures($1, $2)');
Route::get ('admin/picture_tags/(:num)/add', 'admin/picture_tags@add_pictures($1)');
Route::post ('admin/picture_tags/(:num)/create', 'admin/picture_tags@create_pictures($1)');
Route::get ('admin/picture_tags/(:num)/edit/(:num)', 'admin/picture_tags@edit_pictures($1, $2)');
Route::post ('admin/picture_tags/(:num)/update/(:num)', 'admin/picture_tags@update_pictures($1, $2)');
Route::get ('admin/picture_tags/(:num)/destroy/(:num)', 'admin/picture_tags@destroy_pictures($1, $2)');
Route::post ('admin/picture_tags/(:num)/sort', 'admin/picture_tags@sort_pictures($1)');

Route::get ('admin/pictures/(:num)/', 'admin/pictures@index($1)');


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