<?php

Ko_Web_Route::VPost('draft', function()
{
	$loginApi = new KUser_loginApi;
	$uid = $loginApi->iGetLoginUid();
	if ($uid)
	{
		$draftid = $uid;
		$aid = KContent_Api::USER_DRAFT;
	}
	else
	{
		$uuidApi = new KUser_uuidApi;
		$draftid = $uuidApi->iGetId(true);
		$aid = KContent_Api::UUID_DRAFT;
	}
	$contentApi = new KContent_Api;
	if ($contentApi->bSet($aid, $draftid, Ko_Web_Request::SPost('content')))
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
	$render = new KRender_json;
	$render->oSetData($data)->oSend();
});
