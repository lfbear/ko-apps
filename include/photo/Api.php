<?php

class KPhoto_Api extends Ko_Busi_Api
{
	public function addPhoto($image, $title = '', $albumid = 0)
	{
		$loginApi = new KUser_loginApi();
		$uid = $loginApi->iGetLoginUid();
		if (!$uid) {
			return 0;
		}
		if ($albumid) {
			$key = compact('uid', 'albumid');
			$album = $this->albumDao->aGet($key);
			if (empty($album)) {
				$albumid = 0;
			}
		}
		if (!$albumid) {
			$albumid = $this->_albumTag2Id($uid, date('Y-m'));
		}
		if (!$albumid) {
			return 0;
		}
		$data = compact('albumid', 'image');
		$time = time();
		$data['sort'] = $time;
		$date['ctime'] = date('Y-m-d H:i:s', $time);
		$photoid = $this->photoDao->iInsert($data);
		if ($photoid && strlen($title)) {
			$contentApi = new KContent_Api();
			$contentApi->bSet(KContent_Api::PHOTO_TITLE, $photoid, $title);

			$key = compact('uid', 'albumid');
			$album = $this->albumDao->aGet($key);
			$update = array();
			if (empty($album['cover'])) {
				$update = array('cover' => $image);
			}
			$this->albumDao->iUpdate($key, $update, array('pcount' => 1));
		}
		return $photoid;
	}

	private function _albumTag2Id($uid, $albumtag)
	{
		$key = compact('uid', 'albumtag');
		$info = $this->albumtagDao->aGet($key);
		if (!empty($info)) {
			return $info['albumid'];
		}
		$albumid = $this->_addAlbum($uid, $albumtag);
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
