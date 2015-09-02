<?php

Ko_Web_Route::VGet('index', function(){
	$render = new KRender_www;
	$render->oSetTemplate('www/photo/index.html')->oSend();
});

Ko_Web_Route::VGet('user', function(){
	$uid = Ko_Web_Request::IGet('uid');

	$photoApi = new KPhoto_Api();
	$albumlist = $photoApi->getAlbumList($uid, 0, 10, $total);

	$render = new KRender_www;
	$render->oSetTemplate('www/photo/user.html')
		->oSetData('albumlist', $albumlist)
		->oSetData('total', $total)
		->oSetData('uid', $uid)
		->oSend();
});

Ko_Web_Route::VGet('album', function(){
	$uid = Ko_Web_Request::IGet('uid');
	$albumid = Ko_Web_Request::IGet('albumid');

	$photoApi = new KPhoto_Api();
	$albuminfo = $photoApi->getAlbumInfo($uid, $albumid);
	$photolist = empty($albuminfo) ? array() : $photoApi->getPhotoList($uid, $albumid, 0, 10, $total);

	$render = new KRender_www;
	$render->oSetTemplate('www/photo/album.html')
		->oSetData('albuminfo', $albuminfo)
		->oSetData('photolist', $photolist)
		->oSetData('total', $total)
		->oSend();
});
