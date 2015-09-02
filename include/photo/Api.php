<?php

class KPhoto_Api extends Ko_Busi_Api
{
	public function getPhotoInfo($albumid, $photoid)
	{
		$photokey = compact('albumid', 'photoid');
		$info = $this->photoDao->aGet($photokey);
		if (!empty($info)) {
			$contentApi = new KContent_Api();
			$info['title'] = $contentApi->sGetText(KContent_Api::PHOTO_TITLE, $photoid);
		}
		return $info;
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
		$option->oWhere('albumid = ?', $albumid)->oOffset($start)->oLimit($num)->oCalcFoundRows(true)->oOrderBy('sort desc');
		$photolist = $this->photoDao->aGetList($option);
		$total = $option->iGetFoundRows();
		if ($total != $album['pcount']) {
			$this->albumDao->iUpdate($albumkey, array('pcount' => $total));
		}
		$photoids = Ko_Tool_Utils::AObjs2ids($photolist, 'photoid');
		$contentApi = new KContent_Api();
		$aText = $contentApi->aGetText(KContent_Api::PHOTO_TITLE, $photoids);
		$api = new KStorage_Api;
		foreach ($photolist as &$v) {
			$v['image'] = $api->sGetUrl($v['image'], 'imageView2/1/w/150/h/100');
			$v['title'] = $aText[$v['photoid']];
		}
		unset($v);
		return $photolist;
	}

	public function getAlbumList($uid, $start, $num, &$total)
	{
		if (!$uid) {
			$total = 0;
			return array();
		}
		$option = new Ko_Tool_SQL();
		$option->oWhere('uid = ?', $uid)->oOffset($start)->oLimit($num)->oCalcFoundRows(true)->oOrderBy('sort desc');
		$albumlist = $this->albumDao->aGetList($option);
		$total = $option->iGetFoundRows();
		$albumids = Ko_Tool_Utils::AObjs2ids($albumlist, 'albumid');
		$contentApi = new KContent_Api();
		$aInfo = array(
			KContent_Api::PHOTO_ALBUM_TITLE => $albumids,
			KContent_Api::PHOTO_ALBUM_INTRO => $albumids,
		);
		$aText = $contentApi->aGetTextEx($aInfo);
		$api = new KStorage_Api;
		foreach ($albumlist as &$v) {
			$v['cover'] = ('' === $v['cover'])
				? 'http://' . IMG_DOMAIN . '/default/cover.jpg' : $api->sGetUrl($v['cover'], 'imageView2/1/w/150/h/100');
			$v['title'] = $aText[KContent_Api::PHOTO_ALBUM_TITLE][$v['albumid']];
			$v['intro'] = $aText[KContent_Api::PHOTO_ALBUM_INTRO][$v['albumid']];
		}
		unset($v);
		return $albumlist;
	}

	public function deletePhoto($uid, $albumid, $photoid)
	{
		if (!$uid) {
			return false;
		}
		$albumkey = compact('uid', 'albumid');
		$album = $this->albumDao->aGet($albumkey);
		if (empty($album)) {
			return false;
		}
		$recycleid = $this->_albumTag2Id($uid, 'recycle', '回收站');
		$photokey = compact('albumid', 'photoid');
		if ($recycleid == $albumid) {
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
		$recycleid = $this->_albumTag2Id($uid, 'recycle', '回收站');
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
		$data = compact('albumid', 'image');
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
