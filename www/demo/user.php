<?php

Ko_Web_Route::VPost('logout', function()
{
	$api = new KUser_loginApi;
	$api->vSetLoginUid(0);
	Ko_Web_Response::VSetRedirect('/');
	Ko_Web_Response::VSend();
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
