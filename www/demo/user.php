<?php

Ko_Web_Route::VGet('test', function()
{
	$ret = call_user_func_array(array('KUser_loginFacade', 'iLogin'), array('zhangchu', 'zhangchu', &$iErrno));
	var_dump($ret);
	var_dump($iErrno);
});

Ko_Web_Route::VGet('login', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('user/login.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('regist', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('user/regist.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});
