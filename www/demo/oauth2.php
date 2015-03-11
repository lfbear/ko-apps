<?php

Ko_Web_Route::VGet('qq', function()
{
	$api = new KUser_Oauth2_Api;
	$aTokeninfo = $api->vMain('qq', array('KUser_Oauth2_qqApi', 'AGetAccessToken'));
	if (!$api->bGetUserinfoByTokeninfo('qq', $aTokeninfo, $sUsername, $aUserinfo))
	{
		throw new Exception('获取用户信息失败', 0);
	}
	var_dump($sUsername);
	var_dump($aUserinfo);
});

Ko_Web_Route::VGet('weibo', function()
{
	$api = new KUser_Oauth2_Api;
	$aTokeninfo = $api->vMain('weibo', array('KUser_Oauth2_weiboApi', 'AGetAccessToken'));
	if (!$api->bGetUserinfoByTokeninfo('weibo', $aTokeninfo, $sUsername, $aUserinfo))
	{
		throw new Exception('获取用户信息失败', 0);
	}
	var_dump($sUsername);
	var_dump($aUserinfo);
});

Ko_Web_Route::VGet('baidu', function()
{
	$api = new KUser_Oauth2_Api;
	$aTokeninfo = $api->vMain('baidu', 'file_get_contents');
	if (!$api->bGetUserinfoByTokeninfo('baidu', $aTokeninfo, $sUsername, $aUserinfo))
	{
		throw new Exception('获取用户信息失败', 0);
	}
	var_dump($sUsername);
	var_dump($aUserinfo);
});
