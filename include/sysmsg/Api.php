<?php

class KSysmsg_Api extends Ko_Mode_Sysmsg
{
	const PHOTO = 1;

	protected $_aConf = array(
		'content' => 'content',
		'user' => 'user',
		'merge' => 'merge',
		'kind' => array(
			'index' => array(self::PHOTO),
		),
	);
}
