<?php

Ko_Web_Route::VGet('login', function()
{
	$render = new KRender_passport();
	$render->oSetTemplate('passport/user/login.html')->oSend();
});

Ko_Web_Route::VGet('reg', function()
{
	$render = new KRender_passport();
	$render->oSetTemplate('passport/user/reg.html')->oSend();
});

Ko_Web_Route::VGet('logo', function()
{
	$api = new KUser_loginApi;
	$uid = $api->iGetLoginUid();
	if ($uid)
	{
		$render = new KRender_passport();
		$render->oSetTemplate('passport/user/logo.html')->oSend();
	}
	else
	{
		Ko_Web_Response::VSetRedirect('reg');
		Ko_Web_Response::VSend();
	}
});
