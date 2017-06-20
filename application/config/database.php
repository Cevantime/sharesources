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

$db['thibault']['hostname'] = 'localhost';
$db['thibault']['username'] = 'wf3-resources';
$db['thibault']['password'] = 'idkIO4svJWWBqGss';
$db['thibault']['database'] = 'wf3-resources';
$db['thibault']['dbdriver'] =  'mysqli';
$db['thibault']['dbprefix'] =  '';
$db['thibault']['pconnect'] =  FALSE;
$db['thibault']['db_debug'] =  TRUE;
$db['thibault']['cache_on'] =  FALSE;
$db['thibault']['cachedir'] =  'application/cache';
$db['thibault']['char_set'] =  'utf8';
$db['thibault']['dbcollat'] =  'utf8_general_ci';
$db['thibault']['swap_pre'] =  '{PRE}';
$db['thibault']['autoinit'] =  TRUE;
$db['thibault']['stricton'] =  FALSE;
