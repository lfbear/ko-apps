<?php

Ko_Web_Route::VGet('qq', function()
{
	$api = new KUser_loginApi;
	$uid = $api->iOauth2Login('qq');
	$api->vSetLoginUid($uid, 'qq');
	Ko_Web_Response::VSetRedirect('/');
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('weibo', function()
{
	$api = new KUser_loginApi;
	$uid = $api->iOauth2Login('weibo');
	$api->vSetLoginUid($uid, 'weibo');
	Ko_Web_Response::VSetRedirect('/');
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('baidu', function()
{
	$api = new KUser_loginApi;
	$uid = $api->iOauth2Login('baidu');
	$api->vSetLoginUid($uid, 'baidu');
	Ko_Web_Response::VSetRedirect('/');
	Ko_Web_Response::VSend();
});
