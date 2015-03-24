<?php

class KUser_uuidApi extends Ko_Mode_Uuid
{
	protected $_aConf = array(
		'cookiename' => 'uuid',
		'uuid' => 'uuid',
	);
	
	public function __construct()
	{
		$this->_aConf['domain'] = '.'.MAIN_DOMAIN;
		parent::__construct();
	}
}
