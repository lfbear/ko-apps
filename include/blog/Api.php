<?php

class KBlog_Api extends Ko_Busi_Api
{
	public function aGetAllList($offset, $limit)
	{
		$option = new Ko_Tool_SQL;
		return $this->blogDao->aGetList($option->oOrderBy('blogid desc')->oOffset($offset)->oLimit($limit));
	}

	public function aGet($blogid)
	{
		return $this->blogDao->aGet($blogid);
	}
	
	public function iInsert($title, $content)
	{
		$loginApi = new KUser_loginApi;
		$uid = $loginApi->iGetLoginUid();
		if ($uid)
		{
			$data = array(
				'uid' => $uid,
				'ctime' => date('Y-m-d H:i:s'),
			);
			$data['mtime'] = $data['ctime'];
			$blogid = $this->blogDao->iInsert($data);
			if ($blogid)
			{
				$contentApi = new KContent_Api;
				$contentApi->bSet(KContent_Api::BLOG_TITLE, $blogid, $title);
				$contentApi->bSet(KContent_Api::BLOG_CONTENT, $blogid, $content);
				$contentApi->bSet(KContent_Api::USER_DRAFT, $uid, '');
			}
			return $blogid;
		}
		return 0;
	}
	
	public function iUpdate($blogid, $title, $content)
	{
		$loginApi = new KUser_loginApi;
		$uid = $loginApi->iGetLoginUid();
		if ($uid)
		{
			$info = $this->blogDao->aGet($blogid);
			if (!empty($info) && $uid == $info['uid'])
			{
				$contentApi = new KContent_Api;
				$contentApi->bSet(KContent_Api::BLOG_TITLE, $blogid, $title);
				$contentApi->bSet(KContent_Api::BLOG_CONTENT, $blogid, $content);
				return $this->blogDao->iUpdate($blogid, array('mtime' => date('Y-m-d H:i:s')));
			}
		}
		return 0;
	}
	
	public function iDelete($blogid)
	{
		$loginApi = new KUser_loginApi;
		$uid = $loginApi->iGetLoginUid();
		if ($uid)
		{
			$option = new Ko_Tool_SQL;
			return $this->blogDao->iDelete($blogid, $option->oWhere('uid = ?', $uid));
		}
		return 0;
	}
}
