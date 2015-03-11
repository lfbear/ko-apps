<?php

class KStorage_Api extends Ko_Data_Qiniu
{
	protected $_aConf = array(
		'uni' => 'uni',
		'urlmap' => 'urlmap',
		'size' => 'size',
	);
	
	public function __construct()
	{
		parent::__construct('wI_dH99z7FxEDZcD0t5fkV8Y996_JbeCqIzUlEqF',
			'3YGieZJl6u0Yq7qHruHyusAxs4Mq7R7fdGtg2LIY',
			'kophp',
			'7xawfx.com1.z0.glb.clouddn.com');
	}
}
