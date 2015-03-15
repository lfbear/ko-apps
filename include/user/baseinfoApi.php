<?php

class KUser_baseinfoApi extends Ko_Mode_Item
{
	protected $_aConf = array(
		'item' => 'baseinfo',
		'itemlog' => 'changelog',
		'itemlog_kindfield' => 'kind',
		'itemlog_idfield' => 'infoid',
	);
	
	public function aGetMoreInfo($uid = 0, $aMore = array('logo32'))
	{
		if (!$uid)
		{
			$loginApi = new KUser_loginApi;
			$uid = $loginApi->iGetLoginUid();
		}
		$info = parent::aGet($uid);
		if (!empty($info))
		{
			$this->_vFillMoreInfo($info, $aMore);
		}
		return $info;
	}
	
	public function bUpdateOauth2info($uid, $userinfo)
	{
		$data = array(
			'uid' => $uid,
			'nickname' => $userinfo['nickname'],
		);
		if (strlen($userinfo['logo']))
		{
			$api = new KStorage_Api;
			if ($api->bWebUrl2Storage($userinfo['logo'], $logo))
			{
				$data['logo'] = $logo;
			}
		}
		$this->aInsert($data, $data);
		return true;
	}
	
	private function _vFillMoreInfo(&$info, $aMore)
	{
		$api = new KStorage_Api;
		foreach ($aMore as $more)
		{
			switch($more)
			{
			case 'logo16':
				$info['logo16'] = $api->sGetUrl($info['logo'], 'imageView2/1/w/16');
				break;
			case 'logo32':
				$info['logo32'] = $api->sGetUrl($info['logo'], 'imageView2/1/w/32');
				break;
			case 'logo48':
				$info['logo48'] = $api->sGetUrl($info['logo'], 'imageView2/1/w/48');
				break;
			}
		}
	}
}
