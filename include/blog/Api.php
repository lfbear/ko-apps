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
		if ($uid) {
			$data = array(
				'uid' => $uid,
				'ctime' => date('Y-m-d H:i:s'),
				'cover' => $this->_sGetCover($content),
			);
			$data['mtime'] = $data['ctime'];
			$blogid = $this->blogDao->iInsert($data);
			if ($blogid) {
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
		if ($uid) {
			$info = $this->blogDao->aGet($blogid);
			if (!empty($info) && $uid == $info['uid']) {
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
		if ($uid) {
			$option = new Ko_Tool_SQL;
			return $this->blogDao->iDelete($blogid, $option->oWhere('uid = ?', $uid));
		}
		return 0;
	}

	private function _sGetCover($content)
	{
		$offset = 0;
		$storage = new KStorage_Api;
		while (1) {
			$url = $this->_sGetImageUrl($content, $offset);
			if ('' === $url) {
				break;
			}
			list($dest, $brief) = $storage->aParseUrl($url);
			if ('' !== $dest) {
				return $dest;
			}
		}
		return '';
	}

	private function _sGetImageUrl($content, &$offset)
	{
		$spos = strpos($content, ' src=', $offset);
		if (false === $spos) {
			return '';
		}
		$quotes = substr($content, $spos + 5, 1);
		if ('"' !== $quotes && "'" !== $quotes) {
			return '';
		}
		$offset = strpos($content, $quotes, $spos + 6);
		if (false === $offset) {
			return '';
		}
		return substr($content, $spos + 6, $offset - $spos - 6);
	}
}
