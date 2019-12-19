<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
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
$route['default_controller'] = "HomeController";
$route['404_override'] = '';

$route['login'] = 'LoginController/index';
$route['home'] = 'HomeController/index';
$route['bo.*'] = '';

$route['blog.*'] = '';

$route['tags/all'] = 'TagsController/all';
$route['tags/(:any)'] = 'TagsController/courses/$1';

$route['chat/chat(.*)'] = 'ChatController$1';

$route['teachers/search'] = 'TeachersController/search';
$route['teacher/profile'] = 'TeachersController/profile';

$route['teachers(.*)'] = 'admin/UsersController/all/webforceteacher$1';
$route['teacher/add(.*)'] = 'admin/UsersController/add/webforceteacher$1';
$route['teacher/edit(.*)'] = 'admin/UsersController/edit/webforceteacher$1';
$route['teacher/delete(.*)'] = 'admin/UsersController/delete/webforceteacher$1';
$route['administrators(.*)'] = 'admin/UsersController/all/webforceadmin$1';
$route['administrator/add(.*)'] = 'admin/UsersController/add/webforceadmin$1';
$route['administrator/edit(.*)'] = 'admin/UsersController/edit/webforceadmin$1';
$route['administrator/delete(.*)'] = 'admin/UsersController/delete/webforceadmin$1';
$route['admin/configuration'] = 'admin/ConfigurationController/index';
$route['admin/addnotif'] = 'NotificationsController/save';
$route['admin/notifications'] = 'NotificationsController/all';
$route['admin/editnotif/(:num)'] = 'NotificationsController/save/$1';
$route['admin/deletenotif/(:num)'] = 'NotificationsController/delete/$1';

$route['courses'] = 'CoursesController/index';
$route['courses/bootstrap'] = 'CoursesController/bootstrap';
$route['courses/all'] = 'CoursesController/all';
$route['courses/calendar'] = 'CoursesController/calendar';
$route['courses/calendarAjax'] = 'CoursesController/calendarAjax';
$route['courses/courseDetails'] = 'CoursesController/courseDetails';
$route['courses/mines'] = 'CoursesController/mines';
$route['courses/edit/(:num)'] = 'CoursesController/edit/$1';
$route['courses/see/(:num)'] = 'CoursesController/see/$1';
$route['courses/duplicate/(:num)'] = 'CoursesController/duplicate/$1';
$route['courses/requestshare/(:num)'] = 'CoursesController/requestShare/$1';
$route['courses/requestunshare/(:num)'] = 'CoursesController/requestUnshare/$1';
$route['courses/delete/(:num)'] = 'CoursesController/delete/$1';
$route['courses/tag/(:any)'] = 'CoursesController/allByTag/$1';
$route['courses/changedateshare'] = 'CoursesController/changeDateShare';
$route['courses/sendmsg'] = 'CoursesController/sendMsg';
$route['courses/sendmsg/(:num)'] = 'CoursesController/sendMsg/$1';
$route['courses/search'] = 'CoursesController/search';

$route['search'] = 'SearchController/index';

$route['categories/all'] = 'CategoriesController/all';
$route['categories/add'] = 'CategoriesController/add';
$route['categories/edit/(:num)'] = 'CategoriesController/edit/$1';
$route['categories/delete/(:num)'] = 'CategoriesController/delete/$1';

$route['sessions/add'] = 'SessionsController/add';
$route['sessions/all'] = 'SessionsController/all';
$route['sessions/mines'] = 'SessionsController/mines';
$route['sessions/edit/(:num)'] = 'SessionsController/edit/$1';
$route['sessions/setcurrent/(:num)'] = 'SessionsController/setCurrent/$1';
$route['sessions/delete/(:num)'] = 'SessionsController/delete/$1';
$route['sessions/requestshare/(:num)'] = 'SessionsController/requestShare/$1';
$route['sessions/requestunshare/(:num)'] = 'SessionsController/requestUnshare/$1';

$route['notifications/index'] = 'NotificationsController/index';
$route['notifications/see/(:num)'] = 'NotificationsController/see/$1';
$route['notifications/markasseen/(:num)'] = 'NotificationsController/markAsSeen/$1';

$route['userprefs/(:any)/(:any)'] = 'UserprefsController/index/$1/$2';
/* End of file routes.php */
/* Location: ./application/config/routes.php */