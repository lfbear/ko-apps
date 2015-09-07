<?php

class KContent_Api extends Ko_Mode_Content
{
	const USER_DRAFT           = 1;
	const UUID_DRAFT           = 2;
	const BLOG_TITLE           = 3;
	const BLOG_CONTENT         = 4;
	const USER_NICKNAME        = 5;
	const PHOTO_ALBUM_TITLE    = 6;
	const PHOTO_TITLE          = 8;

	protected $_aConf = array(
		'contentApi' => 'Func',
		'app' => array(
			self::USER_DRAFT => array(
				'type' => 'html',
			),
			self::UUID_DRAFT => array(
				'type' => 'html',
			),
			self::BLOG_TITLE => array(
				'type' => 'text',
			),
			self::BLOG_CONTENT => array(
				'type' => 'html',
			),
			self::USER_NICKNAME => array(
				'type' => 'text',
			),
			self::PHOTO_ALBUM_TITLE => array(
				'type' => 'text',
			),
			self::PHOTO_TITLE => array(
				'type' => 'text',
			),
		),
	);
	
	protected function _sDataUrl2Link($sData)
	{
		$api = new KStorage_Api;
		if ($api->bContent2Storage($sData, $sDest))
		{
			return $api->sGetUrl($sDest, 'imageView2/2/w/600/h/600');
		}
		return parent::_sDataUrl2Link($sData);
	}
}
