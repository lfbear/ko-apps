<?php

class KBlog_Api extends Ko_Busi_Api
{
	public function aGetBlogList($uid, $tag, $start, $num, &$total)
	{
		$option = new Ko_Tool_SQL();
		$option->oOffset($start)->oLimit($num)->oOrderBy('blogid desc')->oCalcFoundRows(true);
		if (strlen($tag)) {
			$list = $this->taglistDao->aGetList($option->oWhere('uid = ? and tag = ?', $uid, $tag));
			$infos = $this->blogDao->aGetDetails($list);
			foreach ($list as &$v) {
				$v = $infos[$v['blogid']];
			}
			unset($v);
		} else {
			$list = $this->blogDao->aGetList($option->oWhere('uid = ?', $uid));
		}
		$total = $option->iGetFoundRows();
		return $list;
	}

	public function aGetAllTaginfo($uid)
	{
		$option = new Ko_Tool_SQL();
		return $this->taginfoDao->aGetList($option->oWhere('uid = ?', $uid)->oOrderBy('mtime desc'));
	}

	public function aGet($uid, $blogid)
	{
		$blogkey = compact('uid', 'blogid');
		$info = $this->blogDao->aGet($blogkey);
		if (!empty($info)) {
			$info['tags'] = $this->_aGetTags($info['tags']);
		}
		return $info;
	}

	public function iInsert($uid, $title, $content, $tags)
	{
		if (!$uid) {
			return 0;
		}
		$addtags = $this->_aGetTags($tags);
		$data = array(
			'uid' => $uid,
			'ctime' => date('Y-m-d H:i:s'),
			'cover' => $this->_sGetCover($content),
			'tags' => implode(' ', $addtags),
		);
		$data['mtime'] = $data['ctime'];
		$blogid = $this->blogDao->iInsert($data);
		if ($blogid) {
			$contentApi = new KContent_Api;
			$contentApi->bSet(KContent_Api::BLOG_TITLE, $blogid, $title);
			$contentApi->bSet(KContent_Api::BLOG_CONTENT, $blogid, $content);

			$this->_vAddTags($uid, $blogid, $addtags);
		}
		return $blogid;
	}

	public function iUpdate($uid, $blogid, $title, $content, $tags)
	{
		$blogkey = compact('uid', 'blogid');
		$info = $this->blogDao->aGet($blogkey);
		if (empty($info)) {
			return 0;
		}

		$oldtagarr = $this->_aGetTags($info['tags']);
		$newtagarr = $this->_aGetTags($tags);
		$addtags = array_diff($newtagarr, $oldtagarr);
		$this->_vAddTags($uid, $blogid, $addtags);
		$subtags = array_diff($oldtagarr, $newtagarr);
		$this->_vSubTags($uid, $blogid, $subtags);

		$contentApi = new KContent_Api;
		$contentApi->bSet(KContent_Api::BLOG_TITLE, $blogid, $title);
		$contentApi->bSet(KContent_Api::BLOG_CONTENT, $blogid, $content);

		$update = array(
			'mtime' => date('Y-m-d H:i:s'),
			'tags' => implode(' ', $newtagarr),
		);
		if (0 == strlen($info['cover'])) {
			$update['cover'] = $this->_sGetCover($content);
		}
		return $this->blogDao->iUpdate($blogkey, $update);
	}

	public function iDelete($uid, $blogid)
	{
		$blogkey = compact('uid', 'blogid');
		$info = $this->blogDao->aGet($blogkey);
		if (empty($info)) {
			return 0;
		}
		$subtags = $this->_aGetTags($info['tags']);
		$this->_vSubTags($uid, $blogid, $subtags);
		return $this->blogDao->iDelete($blogkey);
	}

	private function _vAddTags($uid, $blogid, $tags)
	{
		foreach ($tags as $tag) {
			$taglistkey = compact('uid', 'tag', 'blogid');
			try {
				$this->taglistDao->aInsert($taglistkey);

				$mtime = date('Y-m-d H:i:s');
				$taginfokey = compact('uid', 'tag', 'mtime');
				$taginfokey['bcount'] = 1;
				$this->taginfoDao->aInsert($taginfokey, array('mtime' => $mtime), array('bcount' => 1));
			} catch (Exception $e) {
			}
		}
	}

	private function _vSubTags($uid, $blogid, $tags)
	{
		foreach ($tags as $tag) {
			$taglistkey = compact('uid', 'tag', 'blogid');
			if ($this->taglistDao->iDelete($taglistkey)) {
				$taginfokey = compact('uid', 'tag');
				$option = new Ko_Tool_SQL();
				$this->taginfoDao->iUpdate($taginfokey, array(), array('bcount' => -1), $option->oWhere('bcount > ?', 0));
			}
		}
	}

	private function _aGetTags($tags)
	{
		if (!is_array($tags)) {
			$tags = explode(' ', $tags);
		}
		return array_values(array_diff(array_unique($tags), array('')));
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
