<?php

class KRest_User_agent
{
	public static $s_aConf = array(
		'putstylelist' => array(
			'default' => array(
				'screen' => array('hash', array(
					'width' => 'int',
					'height' => 'int',
				)),
			),
		),
	);

	public function put($id, $update, $before = null, $after = null, $put_style)
	{
		KUser_agentApi::set($update);
	}
}
