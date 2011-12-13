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

$route['default_controller'] = "welcome";
$route['404_override'] = '';

$route['rss'] = 'feed';

//Questi sono per Michela che si sbaglia :D
$route['^anan$'] = 'anana';
$route['^amind$'] = 'anana';

$route['change-language/(:any)'] = "welcome/change_language/$1";
//$route['week/(:num)'] = "week/get/$1"; disabled
$route['week/next'] = "week/next_week";
//$route['week/next/ajax'] = "week/next_week/ajax"; disabled
$route['week/next/delete-my-photo']	= "week/delete_my_photo";
$route['i/like/this'] = "week/i_like_photo";
$route['week/(:num)/(:any)'] = "week/get/$1";
$route['week/next/ajax/(:any)'] = "week/next_week/ajax";
$route['week/next/ajax-form/(:any)'] = "week/next_week/ajax-form";


/* End of file routes.php */
/* Location: ./application/config/routes.php */