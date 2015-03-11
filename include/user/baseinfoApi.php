<?php

class KUser_baseinfoApi extends Ko_Mode_Item
{
	protected $_aConf = array(
		'item' => 'baseinfo',
		'itemlog' => 'changelog',
		'itemlog_kindfield' => 'kind',
		'itemlog_idfield' => 'infoid',
	);
	
	public function bUpdateOauth2info($uid, $userinfo)
	{
		$data = array(
			'uid' => $uid,
			'nickname' => $userinfo['nickname'],
		);
		if (strlen($userinfo['logo']))
		{
			$api = new KStorage_Api;
			if ($api->BWebUrl2Storage($userinfo['logo'], $logo))
			{
				$data['logo'] = $logo;
			}
		}
		$this->aInsert($data, $data);
		return true;
	}
}
