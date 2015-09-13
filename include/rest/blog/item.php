<?php

class KRest_Blog_item
{
	public static $s_aConf = array(
		'unique' => array('hash', array(
			'uid' => 'int',
			'blogid' => 'int',
		)),
		'poststylelist' => array(
			'default' => array('hash', array(
				'title' => 'string',
				'content' => 'string',
				'tags' => 'string',
			)),
		),
	);

	public function post($update, $after = null, $post_style = 'default')
	{
		$loginApi = new KUser_loginApi();
		$loginuid = $loginApi->iGetLoginUid();

		if (0 == strlen($update['title'])) {
			throw new Exception('请输入博客标题', 1);
		}

		$blogApi = new KBlog_Api();
		$blogid = $blogApi->iInsert($loginuid, $update['title'], $update['content'], $update['tags']);
		if (!$blogid) {
			throw new Exception('添加博客失败', 2);
		}

		$contentApi = new KContent_Api();
		$contentApi->bSet(KContent_Api::DRAFT_CONTENT, $loginuid, '');
		$contentApi->bSet(KContent_Api::DRAFT_TITLE, $loginuid, '');

		return array('key' => array('uid' => $loginuid, 'blogid' => $blogid));
	}
}