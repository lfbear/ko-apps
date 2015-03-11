<?php

class KUser_Dao extends Ko_Dao_Factory
{
	protected $_aDaoConf = array(
		'username' => array(
			'type' => 'db_single',
			'kind' => 'user_username',
			'key' => array('username', 'src'),
		),
		'bindlog' => array(
			'type' => 'db_single',
			'kind' => 'user_bindlog',
			'key' => 'uid',
		),
		'hashpass' => array(
			'type' => 'db_one',
			'kind' => 'user_hashpass',
			'split' => 'uid',
		),
		'varsalt' => array(
			'type' => 'db_one',
			'kind' => 'user_varsalt',
			'split' => 'uid',
		),
		'persistent' => array(
			'type' => 'db_split',
			'kind' => 'user_cookie',
			'split' => 'uid',
			'key' => 'series',
		),
		'changelog' => array(
			'type' => 'db_single',
			'kind' => 'user_changelog',
			'key' => 'id',
		),
		'baseinfo' => array(
			'type' => 'db_one',
			'kind' => 'user_baseinfo',
			'split' => 'uid',
		),
	);
}
