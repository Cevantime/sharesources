<?php


$active_group = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = '';
$db['default']['password'] = '';
$db['default']['database'] = '';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = 'application/cache';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '{PRE}';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$db['recette']['hostname'] = 'localhost';
$db['recette']['username'] = 'resources';
$db['recette']['password'] = 'resources';
$db['recette']['database'] = 'resources';
$db['recette']['dbdriver'] =  'mysqli';
$db['recette']['dbprefix'] =  '';
$db['recette']['pconnect'] =  FALSE;
$db['recette']['db_debug'] =  TRUE;
$db['recette']['cache_on'] =  FALSE;
$db['recette']['cachedir'] =  'application/cache';
$db['recette']['char_set'] =  'utf8';
$db['recette']['dbcollat'] =  'utf8_general_ci';
$db['recette']['swap_pre'] =  '{PRE}';
$db['recette']['autoinit'] =  TRUE;
$db['recette']['stricton'] =  FALSE;
