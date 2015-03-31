<?php

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
