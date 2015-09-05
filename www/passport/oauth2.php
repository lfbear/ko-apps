<?php

Ko_Web_Route::VGet('qq', function () {
	oauth2login('qq');
});

Ko_Web_Route::VGet('weibo', function () {
	oauth2login('weibo');
});

Ko_Web_Route::VGet('baidu', function () {
	oauth2login('baidu');
});

function oauth2login($src)
{
	$api = new KUser_loginApi;
	$uid = $api->iOauth2Login($src);
	$api->vSetLoginUid($uid, $src);
	Ko_Web_Response::VSetRedirect(KUser_loginrefApi::SGet());
	Ko_Web_Response::VSend();
}
