<?php

Ko_Web_Route::VGet('plupload', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('ko/test/plupload.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('tinymce', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('ko/test/tinymce.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('push', function()
{
	Ko_Web_Response::VSetContentType('text/event-stream');
	Ko_Web_Response::VSend();
	for ($i=0; $i<2; $i++)
	{
		echo 'data:'.date('Y-m-d H:i:s')."\r\n";
		echo 'id:'.(1-$i)."\r\n";
		echo "\r\n";
		flush();
		sleep(1);
	}
	echo 'retry: 5000'."\r\n";
	echo "\r\n";
});

Ko_Web_Route::VGet('index', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('ko/test/index.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});
