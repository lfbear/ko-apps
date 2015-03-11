<?php

class KUser_Oauth2_qqApi extends Ko_Busi_Api
{
	public function bGetUserinfoByTokeninfo($aSrcConf, $aTokeninfo, &$sUsername, &$aUserinfo)
	{
		if (0 == strlen($aTokeninfo['name']))
		{
			return false;
		}
		$sUsername = $aTokeninfo['name'];
		
		$qstr = getenv('QUERY_STRING');
		parse_str($qstr, $astr);
		$uri = 'https://open.t.qq.com/api/user/info?format=json&oauth_consumer_key='.urlencode($aSrcConf['client_id']).'&access_token='.urlencode($aTokeninfo['access_token']).'&openid='.urlencode($astr['openid']).'&oauth_version=2.a';
		$response = file_get_contents($uri);
		$aUserinfo = json_decode($response, true);
		if (0 != $aUserinfo['ret'])
		{
			return false;
		}
		$aUserinfo = array('showname' => $aUserinfo['data']['name'], 'logo' => $aUserinfo['data']['head'].'/100');
		return true;
	}
	
	public static function AGetAccessToken($sUri)
	{
		$response = file_get_contents($sUri);
		parse_str($response, $arr);
		return json_encode($arr);
	}
}
