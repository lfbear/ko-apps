<?php

Ko_Web_Route::VGet('user', function () {
	static $num = 10;

	$uid = Ko_Web_Request::IGet('uid');
	$tag = Ko_Web_Request::SGet('tag');

	$userinfo = Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo80')));

	$blogApi = new KBlog_Api();
	$taginfos = $blogApi->aGetAllTaginfo($uid);
	$bloglist = $blogApi->aGetBlogList($uid, $tag, 0, $num, $total);
	$blogids = Ko_Tool_Utils::AObjs2ids($bloglist, 'blogid');

	$contentApi = new KContent_Api();
	$htmlrender = new Ko_View_Render_HTML($contentApi);
	$htmlrender->oSetData(KContent_Api::BLOG_TITLE, $blogids);
	$htmlrender->oSetData(KContent_Api::BLOG_CONTENT, $blogids);

	$render = new KRender_www;
	$render->oSetTemplate('www/blog/user.html')
		->oSetData('userinfo', $userinfo)
		->oSetData('taginfos', $taginfos)
		->oSetData('bloglist', $bloglist)
		->oSetData('bloghtml', $htmlrender)
		->oSend();
});

Ko_Web_Route::VGet('post', function () {
	$loginApi = new KUser_loginApi();
	$uid = $loginApi->iGetLoginUid();

	$userinfo = Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo80')));

	$blogApi = new KBlog_Api();
	$taginfos = $blogApi->aGetAllTaginfo($uid);

	$contentApi = new KContent_Api();
	$htmlrender = new Ko_View_Render_HTML($contentApi);
	$htmlrender->oSetData(KContent_Api::DRAFT_CONTENT, $uid);
	$htmlrender->oSetData(KContent_Api::DRAFT_TITLE, $uid);

	$render = new KRender_www;
	$render->oSetTemplate('www/blog/post.html')
		->oSetData('userinfo', $userinfo)
		->oSetData('draft', $htmlrender)
		->oSetData('taginfos', $taginfos)
		->oSend();
});
