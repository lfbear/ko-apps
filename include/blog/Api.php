<?php

class KBlog_Api extends Ko_Busi_Api
{
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
			$blogid = $this->blogDao->iInsert($data);
			if ($blogid)
			{
				$contentApi = new KContent_Api;
				$contentApi->bSet(KContent_Api::BLOG_TITLE, $blogid, $title);
				$contentApi->bSet(KContent_Api::BLOG_CONTENT, $blogid, $content);
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
			$key = compact('uid', 'blogid');
			$info = $this->blogDao->aGet($key);
			if (!empty($info))
			{
				$contentApi = new KContent_Api;
				$contentApi->bSet(KContent_Api::BLOG_TITLE, $blogid, $title);
				$contentApi->bSet(KContent_Api::BLOG_CONTENT, $blogid, $content);
				return $this->blogDao->iUpdate($key, array('mtime' => date('Y-m-d H:i:s')));
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
			$key = compact('uid', 'blogid');
			return $this->blogDao->iDelete($key);
		}
		return 0;
	}
}
