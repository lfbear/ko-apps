<?php

Ko_Web_Route::VGet('item', function()
{
	$blogid = Ko_Web_Request::IGet('blogid');
	
	$blogApi = new KBlog_Api;
	$bloginfo = $blogApi->aGet($blogid);
	if (empty($bloginfo))
	{
		Ko_Web_Response::VSetRedirect('/');
		Ko_Web_Response::VSend();
		exit;
	}
	
	$contentApi = new KContent_Api;
	$htmlrender = new Ko_View_Render_HTML($contentApi);
	$htmlrender->oSetData(KContent_Api::BLOG_TITLE, $blogid);
	$htmlrender->oSetData(KContent_Api::BLOG_CONTENT, $blogid);
	
	$bloginfo['uid'] = Ko_Tool_Adapter::VConv($bloginfo['uid'], array('user_baseinfo', array('logo32')));
	
	$render = new KRender_default;
	$render->oSetTemplate('ko/blog/item.html');
	$render->oSetData('bloginfo', $bloginfo);
	$render->oSetData('htmlinfo', $htmlrender);
	
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VPost('post', function()
{
	$title = Ko_Web_Request::SPost('title');
	$content = Ko_Web_Request::SPost('content');
	
	$blogApi = new KBlog_Api;
	$blogid = $blogApi->iInsert($title, $content);
	if ($blogid)
	{
		$data = array(
			'errno' => 0,
			'blogid' => $blogid,
		);
	}
	else
	{
		$data = array(
			'errno' => 1,
			'error' => '请登录后在提交数据',
		);
	}
	$render = new Ko_View_Render_JSON;
	$render->oSetData($data);
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});
