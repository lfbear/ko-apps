<?php

class KRest_Photo_item
{
	public static $s_aConf = array(
		'unique' => 'int',
		'stylelist' => array(
			'default' => array('hash', array(
				'photoid' => 'int',
				'image' => 'string',
				'title' => 'string',
			)),
		),
		'poststylelist' => array(
			'default' => 'any',
		),
	);

	public function post($update, $after = null)
	{
		$file = Ko_Web_Request::AFile('file');
		$api = new KStorage_Api;
		if (!$api->bUpload2Storage($file, $image))
		{
			throw new Exception('文件上传失败', 1);
		}
		$title = $file['name'];
		$photoApi = new KPhoto_Api;
		$photoid = $photoApi->addPhoto($image, $title);
		$data = array('key' => $photoid);
		if (is_array($after))
		{
			switch($after['style'])
			{
				default:
					$data['after'] = compact('photoid', 'image', 'title');
					$data['after']['image'] = $api->sGetUrl($image, $after['decorate']);
					break;
			}
		}
		return $data;
	}
}
