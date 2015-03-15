<?php

Ko_Web_Route::VPost('upload', function()
{
	$file = Ko_Web_Request::AFile('file');
	$api = new KStorage_Api;
	if ($api->bUpload2Storage($file, $sDest))
	{
		$data = array(
			'err' => 0,
			'data' => array(
				'file' => $sDest,
				'file600' => $api->sGetUrl($sDest, 'imageView2/2/w/600/h/600'),
			),
		);
	}
	else
	{
		$data = array(
			'err' => 1,
		);
	}
	$render = new Ko_View_Render_JSON;
	$render->oSetData($data);
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});
