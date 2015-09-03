<?php

class KRest_Photo_item
{
	public static $s_aConf = array(
		'unique' => array('hash', array(
			'uid' => 'int',
			'photoid' => 'int',
		)),
		'stylelist' => array(
			'default' => array('hash', array(
				'photoid' => 'int',
				'albumid' => 'int',
				'uid' => 'int',
				'image' => 'string',
				'ctime' => 'string',
				'title' => 'string',
			)),
		),
		'poststylelist' => array(
			'default' => 'any',
			'album' => 'string',
		),
		'putstylelist' => array(
			'title' => 'string',
		),
	);

	public function str2key($str)
	{
		list($uid, $photoid) = explode('_', $str);
		return compact('uid', 'photoid');
	}

	public function post($update, $after = null, $post_style = 'default')
	{
		$file = Ko_Web_Request::AFile('file');
		$api = new KStorage_Api;
		if (!$api->bUpload2Storage($file, $image)) {
			throw new Exception('文件上传失败', 1);
		}
		$title = $file['name'];

		$loginApi = new KUser_loginApi();
		$uid = $loginApi->iGetLoginUid();

		$photoApi = new KPhoto_Api;
		switch ($post_style)
		{
			case 'album':
				$albumid = $update;
				break;
			default:
				$albumid = 0;
				break;
		}
		$photoid = $photoApi->addPhoto($albumid, $uid, $image, $title);
		$this->_sendSysmsg($uid, $albumid, $photoid);
		$data = array('key' => compact('uid', 'photoid'));
		if (is_array($after)) {
			switch ($after['style']) {
				default:
					$data['after'] = $photoApi->getPhotoInfo($uid, $photoid);
					$data['after']['image'] = $api->sGetUrl($image, $after['decorate']);
					break;
			}
		}
		return $data;
	}

	public function put($id, $update, $before = null, $after = null, $put_style = 'default')
	{
		$loginApi = new KUser_loginApi();
		$uid = $loginApi->iGetLoginUid();
		if ($uid != $id['uid']) {
			throw new Exception('修改照片失败', 1);
		}

		$photoApi = new KPhoto_Api();
		switch ($put_style)
		{
			case 'title':
				$photoApi->changePhotoTitle($uid, $id['photoid'], $update);
				break;
		}
		return array('key' => $id);
	}

	public function delete($id, $before = null)
	{
		$loginApi = new KUser_loginApi();
		$uid = $loginApi->iGetLoginUid();
		if ($uid != $id['uid']) {
			throw new Exception('删除照片失败', 1);
		}

		$photoApi = new KPhoto_Api;
		if (!$photoApi->deletePhoto($uid, $id['photoid'])) {
			throw new Exception('删除照片失败', 2);
		}
		return array('key' => $id);
	}

	private function _sendSysmsg($uid, $albumid, $photoid)
	{
		$photoApi = new KPhoto_Api;
		$content = compact('uid', 'albumid', 'photoid');
		$content['userinfo'] = Ko_Tool_Adapter::VConv($content['uid'], array('user_baseinfo', array('logo16')));
		$content['albuminfo'] = $photoApi->getAlbumInfo($uid, $albumid);
		$content['photolist'] = $photoApi->getPhotoList($uid, $albumid, 0, 5, $total);
		$sysmsgApi = new KSysmsg_Api();
		$sysmsgApi->iSend(0, KSysmsg_Api::PHOTO, $content, $albumid);
	}
}
