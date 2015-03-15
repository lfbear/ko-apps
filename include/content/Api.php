<?php

class KContent_Api extends Ko_Mode_Content
{
	const DRAFT = 1;
	
	protected $_aConf = array(
		'contentApi' => 'Func',
		'app' => array(
			self::DRAFT => array(
				'type' => 'html',
			),
		),
	);
}
