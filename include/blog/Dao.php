<?php

class KBlog_Dao extends Ko_Dao_Factory
{
	protected $_aDaoConf = array(
		'blog' => array(
			'type' => 'db_single',
			'kind' => 'blog_user',
			'key' => 'blogid',
		),
	);
}
