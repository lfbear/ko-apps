<?php

$render = new KRender_default;
$render->oSetTemplate('index.html');

$loginApi = new KUser_loginApi;
$uid = $loginApi->iGetLoginUid();
$contentApi = new KContent_Api;
if ($uid && '' !== trim($contentApi->sGetHtml(KContent_Api::USER_DRAFT, $uid)))
{
	$htmlrender = new Ko_View_Render_HTML($contentApi);
	$htmlrender->oSetData(KContent_Api::USER_DRAFT, $uid);
	$render->oSetData('draft', $htmlrender);
}
else
{
	$uuidApi = new KUser_uuidApi;
	$draftid = $uuidApi->iGetId();
	if ($draftid)
	{
		$htmlrender = new Ko_View_Render_HTML($contentApi);
		$htmlrender->oSetData(KContent_Api::UUID_DRAFT, $draftid);
		$render->oSetData('draft', $htmlrender);
	}
}

Ko_Web_Response::VAppendBody($render);
Ko_Web_Response::VSend();
