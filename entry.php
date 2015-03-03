<?php

define('COMMON_CLASS_PATH', '/usr/share/php/apps/include/');
define('COMMON_CONF_PATH', '/usr/share/php/apps/conf/');
define('COMMON_RUNDATA_PATH', '/usr/share/php/apps/rundata/');

define('KO_INCLUDE_DIR', COMMON_CLASS_PATH);
//mysql -hrdsuurafiuurafi.mysql.rds.aliyuncs.com -udemo -pdemodemo demo
define('KO_DB_HOST', 'rdsuurafiuurafi.mysql.rds.aliyuncs.com');
define('KO_DB_USER', 'demo');
define('KO_DB_PASS', 'demodemo');
define('KO_DB_NAME', 'demo');
define('KO_MC_HOST', 'e77874bc68b911e4.m.cnbjalicm12pub001.ocs.aliyuncs.com:11211');
require_once('/usr/share/php/ko/ko.class.php');

Ko_Web_Event::On('ko.bootstrap', 'before', function()
{
	Ko_Web_Config::VSetConf(COMMON_CONF_PATH.'all.ini', COMMON_RUNDATA_PATH.'all.php');
});

Ko_Web_Event::On('ko.error', '500', function($errno, $errstr, $errfile, $errline, $errcontext)
{
	Ko_Web_Error::V500($errno, $errstr, $errfile, $errline, $errcontext);
	exit;
});

Ko_Web_Event::On('ko.dispatch', '404', function()
{
	Ko_Web_Route::V404();
	exit;
});

require_once(KO_DIR.'web/Bootstrap.php');

