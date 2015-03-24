<?php

$render = new KRender_default;
$render->oSetTemplate('index.html');

$loginApi = new KUser_loginApi;
$uid = $loginApi->iGetLoginUid();
$contentApi = new KContent_Api;
if ($uid && '' !== trim(Ko_Html_ImgParse::sParse($contentApi->sGetHtml(KContent_Api::USER_DRAFT, $uid))))
{	//如果用户登录，并且有草稿
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

if ($uid && $draftid)
{	//用户登录，并且没有草稿，将未登录用户的草稿转移为登录用户的草稿
	$draft = $contentApi->sGetHtml(KContent_Api::UUID_DRAFT, $draftid);
	$contentApi->bSet(KContent_Api::USER_DRAFT, $uid, $draft);
	$contentApi->bSet(KContent_Api::UUID_DRAFT, $draftid, '');
}
