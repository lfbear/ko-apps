<?php

class KRest_Api extends Ko_Mode_Rest
{
	protected $_aConf = array(
		'user' => array(
			'urilist' => array(
				'item' => array(
					'unique' => 'int',
					'stylelist' => array(
						'default' => array(
							'hash', array(
								'uid' => 'int',
								'nickname' => 'string',
								'logo' => 'string',
							),
						),
					),
				),
			),
		),
	);

	protected function _sGetClassname($sModule, $sResource)
	{
		$item = explode('/', $sModule);
		$classname = 'KRest_';
		foreach ($item as $v)
		{
			$classname .= ucfirst($v).'_';
		}
		$classname .= $sResource;
		return $classname;
	}

	public function run()
	{
		$uri = Ko_Web_Request::SGet('uri');
		$method = Ko_Web_Request::SGet('method');
		$req_method = Ko_Web_Request::SRequestMethod(true);
		if ('POST' === $req_method)
		{
			if ('PUT' === $method || 'DELETE' === $method)
			{
				$req_method = $method;
			}
		}
		$input = ('GET' === $req_method) ? $_GET : $_POST;
		unset($input['uri']);
		unset($input['method']);
		if (isset($input['jsondata']))
		{
			$input = json_decode($input['jsondata'], true);
		}

		$rest = new KRest_Api;
		$data = $rest->aCall($req_method, $uri, $input);

		$render = new KRender_json();
		$render->oSetData($data)->oSend();
	}
}
