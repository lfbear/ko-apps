<?php

define('MAIN_DOMAIN', 'yuxiaoyan.cn');
define('KO_DOMAIN', 'ko.'.MAIN_DOMAIN);
define('PASSPORT_DOMAIN', 'passport.'.MAIN_DOMAIN);
define('XHPROF_DOMAIN', 'xhprof.'.MAIN_DOMAIN);
define('IMG_DOMAIN', 'img.'.MAIN_DOMAIN);

define('CODE_ROOT', '/usr/share/php/');
define('COMMON_CLASS_PATH', CODE_ROOT.'apps/include/');
define('COMMON_CONF_PATH', CODE_ROOT.'apps/conf/');
define('COMMON_RUNDATA_PATH', CODE_ROOT.'apps/rundata/');

define('KO_DEBUG', 1);
define('KO_TEMPDIR', COMMON_RUNDATA_PATH.'kotmp/');
define('KO_INCLUDE_DIR', COMMON_CLASS_PATH);
//mysql -hrdsuurafiuurafi.mysql.rds.aliyuncs.com -udemo -pdemodemo demo
define('KO_DB_HOST', 'rdsuurafiuurafi.mysql.rds.aliyuncs.com');
define('KO_DB_USER', 'demo');
define('KO_DB_PASS', 'demodemo');
define('KO_DB_NAME', 'demo');
define('KO_MC_HOST', 'e77874bc68b911e4.m.cnbjalicm12pub001.ocs.aliyuncs.com:11211');
define('KO_SMARTY_INC', CODE_ROOT.'Smarty-3.1.21/libs/Smarty.class.php');
define('KO_TEMPLATE_C_DIR', COMMON_RUNDATA_PATH.'templates_c/');
define('KO_XHPROF', true);
define('KO_XHPROF_LIBDIR', CODE_ROOT.'xhprof/xhprof_lib/');
define('KO_XHPROF_WEBBASE', 'http://'.XHPROF_DOMAIN.'/xhprof_html/');
define('KO_XHPROF_TMPDIR', COMMON_RUNDATA_PATH.'xhprof/');
require_once(CODE_ROOT.'ko/ko.class.php');

Ko_Web_Event::On('ko.bootstrap', 'before', function()
{
	Ko_Web_Config::VSetConf(COMMON_CONF_PATH.'all.ini', COMMON_RUNDATA_PATH.'all.php');
});

Ko_Web_Event::On('ko.config', 'after', function()
{
	$appname = Ko_Web_Config::SGetAppName();
	if ('' === $appname)
	{
		Ko_Web_Response::VSetRedirect('http://'.KO_DOMAIN);
		Ko_Web_Response::VSend();
		exit;
	}
	$host = Ko_Web_Request::SHttpHost();
	if (PASSPORT_DOMAIN === $host)
	{
		KUser_loginrefApi::VInit();
	}
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
