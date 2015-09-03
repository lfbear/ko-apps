<?php

class KPhoto_Api extends Ko_Busi_Api
{
	public function getPhotoInfo($uid, $photoid)
	{
		$photokey = compact('uid', 'photoid');
		$info = $this->photoDao->aGet($photokey);
		if (!empty($info)) {
			$contentApi = new KContent_Api();
			$info['title'] = $contentApi->sGetText(KContent_Api::PHOTO_TITLE, $photoid);
		}
		return $info;
	}

	public function getPrevPhotoInfo($photoinfo)
	{
		$photoid = $photoinfo['prev'];
		if (!$photoid) {
			return array();
		}
		$previnfo = $this->getPhotoInfo($photoinfo['uid'], $photoid);
		if (!empty($previnfo)) {
			if ($previnfo['pos'] + 1 != $photoinfo['pos']) {
				$update = array('pos' => $previnfo['pos'] + 1);
				$this->photoDao->iUpdate($photoinfo, $update);
			}
		}
		return $previnfo;
	}

	public function getNextPhotoInfo($photoinfo)
	{
		$photoid = $photoinfo['next'];
		if (!$photoid) {
			return array();
		}
		$nextinfo = $this->getPhotoInfo($photoinfo['uid'], $photoid);
		if (!empty($nextinfo)) {
			if ($nextinfo['pos'] != $photoinfo['pos'] + 1) {
				$update = array('pos' => $photoinfo['pos'] + 1);
				$this->photoDao->iUpdate($nextinfo, $update);
			}
		}
		return $nextinfo;
	}

	public function getAlbumInfo($uid, $albumid)
	{
		$albumkey = compact('uid', 'albumid');
		$info = $this->albumDao->aGet($albumkey);
		if (!empty($info)) {
			$contentApi = new KContent_Api();
			$aInfo = array(
				KContent_Api::PHOTO_ALBUM_TITLE => array($albumid),
				KContent_Api::PHOTO_ALBUM_INTRO => array($albumid),
			);
			$aText = $contentApi->aGetTextEx($aInfo);
			$info['title'] = $aText[KContent_Api::PHOTO_ALBUM_TITLE][$albumid];
			$info['intro'] = $aText[KContent_Api::PHOTO_ALBUM_INTRO][$albumid];
			$info['isrecycle'] = $info['albumid'] == $this->_getRecycleAlbumid($uid);
		}
		return $info;
	}

	public function getPhotoList($uid, $albumid, $start, $num, &$total)
	{
		if (!$uid) {
			$total = 0;
			return array();
		}
		$albumkey = compact('uid', 'albumid');
		$album = $this->albumDao->aGet($albumkey);
		if (empty($album)) {
			$total = 0;
			return array();
		}
		$option = new Ko_Tool_SQL();
		$offset = ($start > 0) ? $start - 1 : $start;
		$addnum = ($start > 0) ? 2 : 1;
		$limit = $num + $addnum;
		$option->oWhere('albumid = ?', $albumid)->oOffset($offset)->oLimit($limit)->oCalcFoundRows(true)->oOrderBy('sort desc');
		$photolist = $this->photoDao->aGetList($option);
		if ($count = count($photolist)) {
			if ($start > 0) {
				$prev = array_shift($photolist);
				$count --;
				$prev = $prev['photoid'];
			} else {
				$prev = 0;
			}
			if ($count > $num) {
				$next = array_pop($photolist);
				$count --;
				$next = $next['photoid'];
			} else {
				$next = 0;
			}
		}
		$total = $option->iGetFoundRows();
		if ($total != $album['pcount']) {
			$this->albumDao->iUpdate($albumkey, array('pcount' => $total));
		}
		$photoids = Ko_Tool_Utils::AObjs2ids($photolist, 'photoid');
		$contentApi = new KContent_Api();
		$aText = $contentApi->aGetText(KContent_Api::PHOTO_TITLE, $photoids);
		$api = new KStorage_Api;
		foreach ($photolist as $k => &$v) {
			$v['image'] = $api->sGetUrl($v['image'], 'imageView2/2/w/150/h/150');
			$v['title'] = $aText[$v['photoid']];

			$update = array();
			if ($start + $k + 1 != $v['pos']) {
				$update['pos'] = $start + $k + 1;
			}
			if ($k != 0) {
				if ($photolist[$k-1]['photoid'] != $v['prev']) {
					$update['prev'] = $v['prev'] = $photolist[$k-1]['photoid'];
				}
			} else {
				if ($prev != $v['prev']) {
					$update['prev'] = $v['prev'] = $prev;
				}
			}
			if ($k != $count - 1) {
				if ($photolist[$k+1]['photoid'] != $v['next']) {
					$update['next'] = $v['next'] = $photolist[$k+1]['photoid'];
				}
			} else {
				if ($next != $v['next']) {
					$update['next'] = $v['next'] = $next;
				}
			}
			if (!empty($update)) {
				$this->photoDao->iUpdate($v, $update);
			}
		}
		unset($v);
		return $photolist;
	}

	public function getAllAlbumList($uid)
	{
		if (!$uid) {
			$total = 0;
			return array();
		}
		$option = new Ko_Tool_SQL();
		$option->oWhere('uid = ?', $uid)->oOrderBy('sort desc');
		$albumlist = $this->albumDao->aGetList($option);
		$albumids = Ko_Tool_Utils::AObjs2ids($albumlist, 'albumid');
		$contentApi = new KContent_Api();
		$aInfo = array(
			KContent_Api::PHOTO_ALBUM_TITLE => $albumids,
			KContent_Api::PHOTO_ALBUM_INTRO => $albumids,
		);
		$aText = $contentApi->aGetTextEx($aInfo);
		$recycleid = $this->_getRecycleAlbumid($uid);
		$api = new KStorage_Api;
		foreach ($albumlist as &$v) {
			$v['cover'] = ('' === $v['cover'])
				? 'http://' . IMG_DOMAIN . '/default/cover.jpg' : $api->sGetUrl($v['cover'], 'imageView2/2/w/150/h/150');
			$v['title'] = $aText[KContent_Api::PHOTO_ALBUM_TITLE][$v['albumid']];
			$v['intro'] = $aText[KContent_Api::PHOTO_ALBUM_INTRO][$v['albumid']];
			$v['isrecycle'] = $v['albumid'] == $recycleid;
		}
		unset($v);
		return $albumlist;
	}

	public function changePhotoTitle($uid, $photoid, $title)
	{
		if (!$uid) {
			return false;
		}
		$photokey = compact('uid', 'photoid');
		$photo = $this->photoDao->aGet($photokey);
		if (empty($photo)) {
			return false;
		}
		$contentApi = new KContent_Api();
		return $contentApi->bSet(KContent_Api::PHOTO_TITLE, $photoid, $title);
	}

	public function changeAlbumCover($uid, $albumid, $cover)
	{
		if (!$uid) {
			return false;
		}
		$recycleid = $this->_getRecycleAlbumid($uid);
		if ($albumid == $recycleid) {
			return false;
		}
		$albumkey = compact('uid', 'albumid');
		$update = compact('cover');
		$this->albumDao->iUpdate($albumkey, $update);
		return true;
	}

	public function changeAlbumTitle($uid, $albumid, $title)
	{
		if (!$uid) {
			return false;
		}
		$recycleid = $this->_getRecycleAlbumid($uid);
		if ($albumid == $recycleid) {
			return false;
		}
		$albumkey = compact('uid', 'albumid');
		$album = $this->albumDao->aGet($albumkey);
		if (empty($album)) {
			return false;
		}
		$contentApi = new KContent_Api();
		return $contentApi->bSet(KContent_Api::PHOTO_ALBUM_TITLE, $albumid, $title);
	}

	public function changeAlbumIntro($uid, $albumid, $intro)
	{
		if (!$uid) {
			return false;
		}
		$recycleid = $this->_getRecycleAlbumid($uid);
		if ($albumid == $recycleid) {
			return false;
		}
		$albumkey = compact('uid', 'albumid');
		$album = $this->albumDao->aGet($albumkey);
		if (empty($album)) {
			return false;
		}
		$contentApi = new KContent_Api();
		return $contentApi->bSet(KContent_Api::PHOTO_ALBUM_INTRO, $albumid, $intro);
	}

	public function deletePhoto($uid, $photoid)
	{
		if (!$uid) {
			return false;
		}
		$photokey = compact('uid', 'photoid');
		$photo = $this->photoDao->aGet($photokey);
		if (empty($photo)) {
			return false;
		}
		$albumkey = array(
			'uid' => $uid,
			'albumid' => $photo['albumid'],
		);
		$recycleid = $this->_getRecycleAlbumid($uid);
		if ($recycleid == $photo['albumid']) {
			$this->photoDao->iDelete($photokey);
			$option = new Ko_Tool_SQL();
			$this->albumDao->iUpdate($albumkey, array(), array('pcount' => -1), $option->oWhere('pcount > ?', 0));
		} else {
			$this->photoDao->iUpdate($photokey, array('albumid' => $recycleid));
			$option = new Ko_Tool_SQL();
			$this->albumDao->iUpdate($albumkey, array(), array('pcount' => -1), $option->oWhere('pcount > ?', 0));
			$recyclekey = array(
				'uid' => $uid,
				'albumid' => $recycleid,
			);
			$this->albumDao->iUpdate($recyclekey, array(), array('pcount' => 1));
		}
		return true;
	}

	public function deleteAlbum($uid, $albumid)
	{
		if (!$uid) {
			return false;
		}
		$recycleid = $this->_getRecycleAlbumid($uid);
		if ($albumid == $recycleid) {
			return false;
		}
		$albumkey = compact('uid', 'albumid');
		$album = $this->albumDao->aGet($albumkey);
		if (empty($album)) {
			return false;
		}
		$option = new Ko_Tool_SQL();
		$pcount = $this->photoDao->iUpdateByCond($option->oWhere('albumid = ?', $albumid), array('albumid' => $recycleid));
		$this->albumDao->iDelete($albumkey);
		$recyclekey = array(
			'uid' => $uid,
			'albumid' => $recycleid,
		);
		$this->albumDao->iUpdate($recyclekey, array(), array('pcount' => $pcount));
		return true;
	}

	public function addPhoto(&$albumid, $uid, $image, $title = '')
	{
		if (!$uid) {
			return 0;
		}
		if ($albumid) {
			$albumkey = compact('uid', 'albumid');
			$album = $this->albumDao->aGet($albumkey);
			if (empty($album)) {
				$albumid = 0;
			}
		}
		if (!$albumid) {
			$albumtag = date('Y-m');
			$albumid = $this->_albumTag2Id($uid, $albumtag, $albumtag);
		}
		if (!$albumid) {
			return 0;
		}
		$data = compact('albumid', 'uid', 'image');
		$time = time();
		$data['sort'] = $time;
		$data['ctime'] = date('Y-m-d H:i:s', $time);
		$photoid = $this->photoDao->iInsert($data);
		if ($photoid) {
			if (strlen($title)) {
				$contentApi = new KContent_Api();
				$contentApi->bSet(KContent_Api::PHOTO_TITLE, $photoid, $title);
			}

			$albumkey = compact('uid', 'albumid');
			$album = $this->albumDao->aGet($albumkey);
			$update = empty($album['cover']) ? array('cover' => $image) : array();
			$time = time();
			$update['sort'] = $time;
			$update['mtime'] = date('Y-m-d H:i:s', $time);
			$this->albumDao->iUpdate($albumkey, $update, array('pcount' => 1));
		}
		return $photoid;
	}

	public function addAlbum($uid, $title, $intro = '')
	{
		if (!$uid) {
			return 0;
		}
		return $this->_addAlbum($uid, $title, $intro);
	}

	private function _getRecycleAlbumid($uid)
	{
		return $this->_albumTag2Id($uid, 'recycle', '回收站');
	}

	private function _albumTag2Id($uid, $albumtag, $albumtitle)
	{
		$albumtagkey = compact('uid', 'albumtag');
		$info = $this->albumtagDao->aGet($albumtagkey);
		if (!empty($info)) {
			$albumid = $info['albumid'];
			$albumkey = compact('uid', 'albumid');
			$albuminfo = $this->albumDao->aGet($albumkey);
			if (!empty($albuminfo)) {
				return $info['albumid'];
			}
			$this->albumtagDao->iDelete($albumtagkey);
		}
		$albumid = $this->_addAlbum($uid, $albumtitle);
		if ($albumid) {
			$data = compact('uid', 'albumtag', 'albumid');
			$data['ctime'] = date('Y-m-d H:i:s');
			$this->albumtagDao->aInsert($data);
		}
		return $albumid;
	}

	private function _addAlbum($uid, $title, $intro = '')
	{
		$time = time();
		$data = array(
			'uid' => $uid,
			'sort' => $time,
			'ctime' => date('Y-m-d H:i:s', $time),
			'mtime' => date('Y-m-d H:i:s', $time),
		);
		$albumid = $this->albumDao->iInsert($data);
		if ($albumid) {
			$contentApi = new KContent_Api();
			$contentApi->bSet(KContent_Api::PHOTO_ALBUM_TITLE, $albumid, $title);
			if (strlen($intro)) {
				$contentApi->bSet(KContent_Api::PHOTO_ALBUM_INTRO, $albumid, $intro);
			}
		}
		return $albumid;
	}
}
