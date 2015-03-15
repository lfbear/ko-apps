<?php

$render = new KRender_default;
$render->oSetTemplate('index.html');

$loginApi = new KUser_loginApi;
$uid = $loginApi->iGetLoginUid();
if ($uid)
{
	$htmlrender = new Ko_View_Render_HTML(new KContent_Api);
	$htmlrender->oSetData(KContent_Api::DRAFT, $uid);
	$render->oSetData('draft', $htmlrender);
}

Ko_Web_Response::VAppendBody($render);
Ko_Web_Response::VSend();
