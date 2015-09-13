<?php

class KRest_Blog_draft
{
	public static $s_aConf = array(
		'putstylelist' => array(
			'default' => array(
				'title' => 'string',
				'content' => 'string',
			),
			'title' => array(
				'title' => 'string',
			),
		),
	);

	public function put($id, $update, $before = null, $after = null, $put_style = 'default')
	{
		$loginApi = new KUser_loginApi();
		$loginuid = $loginApi->iGetLoginUid();

		if ($loginuid) {
			$contentApi = new KContent_Api;
			switch ($put_style)
			{
				case 'default':
					$contentApi->bSet(KContent_Api::DRAFT_CONTENT, $loginuid, $update['content']);
					$contentApi->bSet(KContent_Api::DRAFT_TITLE, $loginuid, $update['title']);
					break;
				case 'title':
					$contentApi->bSet(KContent_Api::DRAFT_TITLE, $loginuid, $update['title']);
					break;
			}
		}
	}
}
