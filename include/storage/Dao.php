<?php

class KStorage_Dao extends Ko_Dao_Factory
{
	protected $_aDaoConf = array(
		'uni' => array(
			'type' => 'db_single',
			'kind' => 'image_uni',
			'key' => array('md5'),
		),
		'info' => array(
			'type' => 'db_single',
			'kind' => 'image_info',
			'key' => array('dest'),
		),
		'size' => array(
			'type' => 'db_single',
			'kind' => 'image_size',
			'key' => array('dest'),
		),
		'urlmap' => array(
			'type' => 'db_single',
			'kind' => 'image_urlmap',
			'key' => array('url'),
		),
	);
}
