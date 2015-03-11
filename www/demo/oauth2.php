<?php

Ko_Web_Route::VGet('qq', function()
{
	$api = new KUser_loginApi;
	$uid = $api->iOauth2Login('qq');
	var_dump($uid);
});

Ko_Web_Route::VGet('weibo', function()
{
	$api = new KUser_loginApi;
	$uid = $api->iOauth2Login('weibo');
	var_dump($uid);
});

Ko_Web_Route::VGet('baidu', function()
{
	$api = new KUser_loginApi;
	$uid = $api->iOauth2Login('baidu');
	var_dump($uid);
});
