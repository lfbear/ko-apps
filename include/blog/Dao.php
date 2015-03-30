<?php

class KBlog_Dao extends Ko_Dao_Factory
{
	protected $_aDaoConf = array(
		'blog' => array(
			'type' => 'db_split',
			'kind' => 'blog_user',
			'split' => 'uid',
			'key' => 'blogid',
		),
	);
}
