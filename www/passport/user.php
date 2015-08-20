<?php

Ko_Web_Route::VPost('logout', function()
{
	$api = new KUser_loginApi;
	$api->vSetLoginUid(0);
	Ko_Web_Response::VSetRedirect(KUser_loginrefApi::SGet());
	Ko_Web_Response::VSend();
});

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
	$render = new KRender_passport();
	$render->oSetTemplate('passport/user/logo.html')->oSend();
});
