<?php

Ko_Web_Route::VPost('upload', function()
{
	$file = Ko_Web_Request::AFile('file');
	$api = new KStorage_Api;
	if ($api->bUpload2Storage($file, $sDest))
	{
		$data = array(
			'errno' => 0,
			'data' => array(
				'file' => $sDest,
				'file600' => $api->sGetUrl($sDest, 'imageView2/2/w/600/h/600'),
			),
		);
	}
	else
	{
		$data = array(
			'errno' => 1,
			'error' => '文件上传失败',
		);
	}
	$render = new KRender_json;
	$render->oSetData($data)->oSend();
});
