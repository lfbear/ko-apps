<?php

class KRest_Photo_item
{
	public static $s_aConf = array(
		'unique' => 'int',
		'stylelist' => array(
			'default' => array('hash', array(
				'photoid' => 'int',
				'image' => 'string',
				'title' => 'string',
			)),
		),
		'poststylelist' => array(
			'default' => array('hash', array(

			)),
		),
	);

	public function post($update, $after = null)
	{
	}
}
