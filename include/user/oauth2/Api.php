<?php

class KUser_Oauth2_Api extends Ko_Mode_OAuth2Client
{
	protected $_aConf = array(
		'usertoken' => 'usertoken',
		'lasttoken' => 'lasttoken',
		'srclist' => array(
			'qq' => array(
				'client_id' => '101198149',
				'client_secret' => '5f024b109b679f4a9245a009c7321776',
				'authorize_uri' => 'https://graph.qq.com/oauth2.0/authorize',
				'token_uri' => 'https://graph.qq.com/oauth2.0/token',
//				'redirect_uri' => 'http://'.KO_DOMAIN.'/oauth2/qq',
				'request_method' => 'GET',
				),
			'weibo' => array(
				'client_id' => '3702295420',
				'client_secret' => 'a517a10d7de758ede4393284ec936356',
				'authorize_uri' => 'https://api.weibo.com/oauth2/authorize',
				'token_uri' => 'https://api.weibo.com/oauth2/access_token',
//				'redirect_uri' => 'http://'.KO_DOMAIN.'/oauth2/weibo',
				'request_method' => 'POST',
				),
			'baidu' => array(
				'client_id' => 'qKjm8H2UaHjCpbfazpwimSFq',
				'client_secret' => 'IbB7GXNfB3mG5eDD2kRfIAbUgYtCgvM1',
				'authorize_uri' => 'https://openapi.baidu.com/oauth/2.0/authorize',
				'token_uri' => 'https://openapi.baidu.com/oauth/2.0/token',
//				'redirect_uri' => 'http://'.KO_DOMAIN.'/oauth2/baidu',
				'request_method' => 'GET',
				),
			),
		);
	
	public function __construct()
	{
		foreach ($this->_aConf['srclist'] as $k => &$v)
		{
			$v['redirect_uri'] = 'http://'.KO_DOMAIN.'/oauth2/'.$k;
		}
		unset($v);
	}

	public function bGetUserinfoByTokeninfo($sSrc, $aTokeninfo, &$sUsername, &$aUserinfo)
	{
		$srcApi = $sSrc.'Api';
		return $this->$srcApi->bGetUserinfoByTokeninfo($this->_aConf['srclist'][$sSrc], $aTokeninfo, $sUsername, $aUserinfo);
	}
}
