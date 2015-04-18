<?php

$offset = Ko_Web_Request::IGet('offset');

$render = new KRender_default;
$render->oSetTemplate('ko/index.html');

$contentApi = new KContent_Api;
$blogApi = new KBlog_Api;
$bloglist = $blogApi->aGetAllList($offset, 10);
$bloglist = Ko_Tool_Adapter::VConv($bloglist, array('list', array('hash', array(
	'blogid' => 'int',
	'uid' => array('user_baseinfo', array('logo32')),
	'ctime' => 'string',
	'mtime' => 'string',
))));
$blogids = Ko_Tool_Utils::AObjs2ids($bloglist, 'blogid');
$htmlrender = new Ko_View_Render_HTML($contentApi);
$htmlrender->oSetData(array(KContent_Api::BLOG_TITLE => $blogids, KContent_Api::BLOG_CONTENT => array('ids' => $blogids, 'maxlength' => 1000)));
$render->oSetData('bloghtml', $htmlrender);
$render->oSetData('bloglist', $bloglist);

$loginApi = new KUser_loginApi;
$uid = $loginApi->iGetLoginUid();
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
