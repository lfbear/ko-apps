<?php

Ko_Web_Route::VGet('user', function(){
	$uid = Ko_Web_Request::IGet('uid');

	$photoApi = new KPhoto_Api();
	$albumlist = $photoApi->getAllAlbumList($uid);
	$userinfo = Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo16')));

	$render = new KRender_www;
	$render->oSetTemplate('www/photo/user.html')
		->oSetData('userinfo', $userinfo)
		->oSetData('albumlist', $albumlist)
		->oSend();
});

Ko_Web_Route::VGet('album', function(){
	static $num = 20;

	$uid = Ko_Web_Request::IGet('uid');
	$albumid = Ko_Web_Request::IGet('albumid');
	$pageno = max(1, Ko_Web_Request::IGet('pageno'));

	$photoApi = new KPhoto_Api();
	$albuminfo = $photoApi->getAlbumInfo($uid, $albumid);
	$userinfo = Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo16')));
	$photolist = empty($albuminfo) ? array() : $photoApi->getPhotoList($uid, $albumid, ($pageno - 1) * $num, $num, $total);
	if (empty($photolist) && $pageno > 1) {
		Ko_Web_Response::VSetRedirect('?uid='.$uid.'&albumid='.$albumid);
		Ko_Web_Response::VSend();
		exit;
	}

	$render = new KRender_www;
	$render->oSetTemplate('www/photo/album.html')
		->oSetData('userinfo', $userinfo)
		->oSetData('albuminfo', $albuminfo)
		->oSetData('photolist', $photolist)
		->oSetData('page', array(
			'num' => $num,
			'no' => $pageno,
			'data_total' => $total,
		))
		->oSend();
});

Ko_Web_Route::VGet('item', function(){
	$uid = Ko_Web_Request::IGet('uid');
	$photoid = Ko_Web_Request::IGet('photoid');

	$storageApi = new KStorage_Api();
	$photoApi = new KPhoto_Api();
	$photoinfo = $photoApi->getPhotoInfo($uid, $photoid);
	$photoinfo['image_src'] = $storageApi->sGetUrl($photoinfo['image'], '');
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
