<?php

class KRest_Photo_item
{
	public static $s_aConf = array(
		'unique' => array('hash', array(
			'albumid' => 'int',
			'photoid' => 'int',
		)),
		'stylelist' => array(
			'default' => array('hash', array(
				'photoid' => 'int',
				'albumid' => 'int',
				'image' => 'string',
				'ctime' => 'string',
				'title' => 'string',
			)),
		),
		'poststylelist' => array(
			'default' => 'any',
		),
	);

	public function str2key($str)
	{
		list($albumid, $photoid) = explode('_', $str);
		return compact('albumid', 'photoid');
	}

	public function post($update, $after = null)
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
		$albumid = 0;
		$photoid = $photoApi->addPhoto($albumid, $uid, $image, $title);
		$data = array('key' => compact('albumid', 'photoid'));
		if (is_array($after)) {
			switch ($after['style']) {
				default:
					$data['after'] = $photoApi->getPhotoInfo($albumid, $photoid);
					$data['after']['image'] = $api->sGetUrl($image, $after['decorate']);
					break;
			}
		}
		return $data;
	}

	public function delete($id, $before = null)
	{
		$loginApi = new KUser_loginApi();
		$uid = $loginApi->iGetLoginUid();

		$photoApi = new KPhoto_Api;
		if (!$photoApi->deletePhoto($uid, $id['albumid'], $id['photoid'])) {
			throw new Exception('删除照片失败', 1);
		}
		return array('key' => $id);
	}
}
