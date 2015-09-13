<?php

Ko_Web_Route::VGet('user', function () {
	$uid = Ko_Web_Request::IGet('uid');

	$userinfo = Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo80')));

	$render = new KRender_www;
	$render->oSetTemplate('www/blog/user.html')
		->oSetData('userinfo', $userinfo)
		->oSend();
});

Ko_Web_Route::VGet('post', function () {
	$loginApi = new KUser_loginApi();
	$uid = $loginApi->iGetLoginUid();

	$userinfo = Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo80')));

	$contentApi = new KContent_Api();
	$htmlrender = new Ko_View_Render_HTML($contentApi);
	$htmlrender->oSetData(KContent_Api::DRAFT_CONTENT, $uid);
	$htmlrender->oSetData(KContent_Api::DRAFT_TITLE, $uid);

	$render = new KRender_www;
	$render->oSetTemplate('www/blog/post.html')
		->oSetData('userinfo', $userinfo)
		->oSetData('draft', $htmlrender)
		->oSend();
});
