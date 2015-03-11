<?php

class KUser_loginApi extends Ko_Mode_User
{
	protected $_aConf = array(
		'username' => 'username',
		'bindlog' => 'bindlog',
		'hashpass' => 'hashpass',
		'varsalt' => 'varsalt',
		'persistent' => 'persistent',
		'persistent_strict' => false,
	);
	
	public function iOauth2Login($sSrc)
	{
		switch($sSrc)
		{
		case 'qq':
			$fnGetToken = array('KUser_Oauth2_qqApi', 'AGetAccessToken');
			break;
		case 'weibo':
			$fnGetToken = array('KUser_Oauth2_weiboApi', 'AGetAccessToken');
			break;
		case 'baidu':
			$fnGetToken = 'file_get_contents';
			break;
		}
		$aTokeninfo = $this->oauth2_Api->vMain($sSrc, $fnGetToken);
		if (!$this->oauth2_Api->bGetUserinfoByTokeninfo($sSrc, $aTokeninfo, $sUsername, $aUserinfo))
		{
			return 0;
		}
		$uid = $this->_iGetExternalUid($sUsername, $sSrc);
		if ($uid)
		{
			$this->oauth2_Api->bSaveUserToken($sSrc, $uid, $aTokeninfo);
		}
		return $uid;
	}
	
	private function _iGetExternalUid($sUsername, $sSrc)
	{
		$uid = $this->iRegisterExternal($sUsername, $sSrc, $iErrno);
		if (!$uid && Ko_Mode_User::E_REGISTER_ALREADY == $iErrno)
		{
			$uid = $this->iIsRegister($sUsername, $sSrc);
		}
		return $uid;
	}
}
