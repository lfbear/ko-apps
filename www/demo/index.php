<?php

$render = new KRender_default;
$render->oSetTemplate('index.html');

$loginApi = new KUser_loginApi;
$uid = $loginApi->iGetLoginUid();
if ($uid)
{
	$htmlrender = new Ko_View_Render_HTML(new KContent_Api);
	$htmlrender->oSetData(KContent_Api::USER_DRAFT, $uid);
	$render->oSetData('draft', $htmlrender);
}
else
{
	$uuidApi = new KUser_uuidApi;
	$uuid = $uuidApi->iGetId();
	if ($uuid)
	{
		$htmlrender = new Ko_View_Render_HTML(new KContent_Api);
		$htmlrender->oSetData(KContent_Api::UUID_DRAFT, $uuid);
		$render->oSetData('draft', $htmlrender);
	}
}

Ko_Web_Response::VAppendBody($render);
Ko_Web_Response::VSend();
