<?php

class KRest_Image_item
{
	public static $s_aConf = array(
		'unique' => 'string',
		'stylelist' => array(
			'default' => 'string',
		),
		'poststylelist' => array(
			'default' => 'any',
		),
	);

	public function post($update, $after = null)
	{
		$file = Ko_Web_Request::AFile('file');
		$api = new KStorage_Api;
		if (!$api->bUpload2Storage($file, $sDest))
		{
			throw new Exception('文件上传失败', 1);
		}
		$data = array('key' => $sDest);
		if (is_array($after))
		{
			switch($after['style'])
			{
				default:
					$data['after'] = $api->sGetUrl($sDest, $after['decorate']);
					break;
			}
		}
		return $data;
	}
}
