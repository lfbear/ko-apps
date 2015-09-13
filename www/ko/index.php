<?php

$offset = Ko_Web_Request::IGet('offset');

$render = new KRender_ko;
$render->oSetTemplate('ko/index.html');

$contentApi = new KContent_Api;
$blogApi = new KBlog_Api;
$bloglist = $blogApi->aGetAllList($offset, 10);
$bloglist = Ko_Tool_Adapter::VConv($bloglist, array('list', array('hash', array(
	'blogid' => 'int',
	'uid' => array('user_baseinfo', array('logo32')),
	'cover' => array('image_baseinfo', array('withsize' => true, 'briefCallback' => function ($info) {
		if (isset($info['size'])) {
			$ratio = $info['size']['width'] / $info['size']['height'];
			if ($ratio >= 16 / 9) {
				return 'imageView2/1/w/720/h/405';
			} else if ($ratio <= 9 / 16) {
				return 'imageView2/1/w/720/h/1280';
			}
		}
		return 'imageView2/2/w/720/h/1280';
	})),
	'ctime' => 'string',
	'mtime' => 'string',
))));
$blogids = Ko_Tool_Utils::AObjs2ids($bloglist, 'blogid');
$htmlrender = new Ko_View_Render_HTML($contentApi);
$htmlrender->oSetData(array(KContent_Api::BLOG_TITLE => $blogids, KContent_Api::BLOG_CONTENT => array('ids' => $blogids, 'maxlength' => 1000)));
$render->oSetData('bloghtml', $htmlrender);
$render->oSetData('bloglist', $bloglist);

$render->oSend();
