<?php

Ko_Web_Route::VGet('index', function(){
	$render = new KRender_www;
	$render->oSetTemplate('www/photo/index.html')->oSend();
});
