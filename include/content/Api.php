<?php

class KContent_Api extends Ko_Mode_Content
{
	const USER_DRAFT = 1;
	const UUID_DRAFT = 2;
	
	protected $_aConf = array(
		'contentApi' => 'Func',
		'app' => array(
			self::USER_DRAFT => array(
				'type' => 'html',
			),
			self::UUID_DRAFT => array(
				'type' => 'html',
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
