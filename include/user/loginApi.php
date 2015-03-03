<?php

class KUser_loginApi extends Ko_Mode_User
{
	protected $_aConf = array(
		'username' => 'username',
		'bindlog' => 'bindlog',
		'hashpass' => 'hashpass',
		'varsalt' => 'varsalt',
		'persistent' => 'persistent',
		'persistent_strict' => false,
	);
}
