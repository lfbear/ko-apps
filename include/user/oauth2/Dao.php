<?php

class KUser_Oauth2_Dao extends Ko_Dao_Factory
{
	protected $_aDaoConf = array(
		'usertoken' => array(
			'type' => 'db_split',
			'kind' => 'oauth2_usertoken',
			'split' => 'uid',
			'key' => array('src', 'token'),
		),
		'lasttoken' => array(
			'type' => 'db_split',
			'kind' => 'oauth2_usertoken_last',
			'split' => 'uid',
			'key' => 'src',
		),
	);
}
