<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

$config['mailtype'] = 'html';

$config['mailpath'] = "/usr/sbin/sendmail";
$config['protocol'] = "smtp";
$config['smtp_host'] = 'ssl://ssl0.ovh.net';
$config['smtp_port'] = "465";
$config['smtp_user'] = 'thibault@make-me-viral.com';
$config['smtp_pass'] = 'Concerto1enDO#';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['wordwrap'] = TRUE;
