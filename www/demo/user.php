<?php

Ko_Web_Route::VPost('logout', function()
{
	$api = new KUser_loginApi;
	$api->vSetLoginUid(0);
	Ko_Web_Response::VSetRedirect('/');
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VPost('draft', function()
{
	$loginApi = new KUser_loginApi;
	$uid = $loginApi->iGetLoginUid();
	if ($uid)
	{
		$contentApi = new KContent_Api;
		if ($contentApi->bSet(0, $uid, Ko_Web_Request::SPost('content')))
		{
			$data = array(
				'errno' => 0,
			);
		}
		else
		{
			$data = array(
				'errno' => 2,
				'error' => '保存失败',
			);
		}
	}
	else
	{
		$data = array(
			'errno' => 1,
			'error' => '请先登录',
		);
	}
	$render = new Ko_View_Render_JSON;
	$render->oSetData($data);
	Ko_Web_Response::VAppendBody($render);
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
