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

Ko_Web_Route::VGet('item', function(){
	$uid = Ko_Web_Request::IGet('uid');
	$photoid = Ko_Web_Request::IGet('photoid');

	$storageApi = new KStorage_Api();
	$photoApi = new KPhoto_Api();
	$photoinfo = $photoApi->getPhotoInfo($uid, $photoid);
	$photoinfo['image'] = $storageApi->sGetUrl($photoinfo['image'], 'imageView2/2/w/600/h/600');
	$albuminfo = $photoApi->getAlbumInfo($uid, $photoinfo['albumid']);
	$userinfo = Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo16')));

	$prevlist = $nextlist = array();
	$curinfo = $photoinfo;
	while (!empty($curinfo = $photoApi->getPrevPhotoInfo($curinfo))) {
		$curinfo['image'] = $storageApi->sGetUrl($curinfo['image'], 'imageView2/2/w/100/h/100');
		array_unshift($prevlist, $curinfo);
		if (count($prevlist) >= 3) {
			break;
		}
	}
	$curinfo = $photoinfo;
	while (!empty($curinfo = $photoApi->getNextPhotoInfo($curinfo))) {
		$curinfo['image'] = $storageApi->sGetUrl($curinfo['image'], 'imageView2/2/w/100/h/100');
		array_push($nextlist, $curinfo);
		if (count($nextlist) >= 3) {
			break;
		}
	}

	$render = new KRender_www;
	$render->oSetTemplate('www/photo/item.html')
		->oSetData('userinfo', $userinfo)
		->oSetData('albuminfo', $albuminfo)
		->oSetData('photoinfo', $photoinfo)
		->oSetData('prevlist', $prevlist)
		->oSetData('nextlist', $nextlist)
		->oSend();
});
