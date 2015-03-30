<?php

Ko_Web_Route::VPost('logout', function()
{
	$api = new KUser_loginApi;
	$api->vSetLoginUid(0);
	Ko_Web_Response::VSetRedirect(KUser_loginrefApi::SGet());
	Ko_Web_Response::VSend();
});
