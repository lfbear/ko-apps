<?php

Ko_Web_Route::VGet('plupload', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('test/plupload.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});
