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
