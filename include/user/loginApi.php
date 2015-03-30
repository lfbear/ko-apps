<?php

class KUser_loginApi extends Ko_Mode_User
{
	const SESSION_TOKEN_NAME = 's';
	
	protected $_aConf = array(
		'username' => 'username',
		'bindlog' => 'bindlog',
		'hashpass' => 'hashpass',
		'varsalt' => 'varsalt',
		'persistent' => 'persistent',
		'persistent_strict' => false,
	);
	
	public function iGetLoginUid(&$exinfo = '')
	{
		static $s_iUid;
		if (is_null($s_iUid))
		{
			$token = Ko_Web_Request::SCookie(self::SESSION_TOKEN_NAME);
			$s_iUid = $this->iCheckSessionToken($token, $exinfo, $iErrno);
			$this->vSetLoginUid($s_iUid, $exinfo);
		}
		return $s_iUid;
	}
	
	public function vSetLoginUid($uid, $exinfo = '')
	{
		$token = $uid ? $this->sGetSessionToken($uid, $exinfo) : '';
		Ko_Web_Response::VSetCookie(self::SESSION_TOKEN_NAME, $token, 0, '/', '.'.MAIN_DOMAIN);
	}
	
	public function iOauth2Login($sSrc)
	{
		$aTokeninfo = $this->oauth2_Api->aGetTokenInfo($sSrc);
		if (!$this->oauth2_Api->bGetUserinfoByTokeninfo($sSrc, $aTokeninfo, $sUsername, $aUserinfo))
		{
			return 0;
		}
		$uid = $this->_iGetExternalUid($sUsername, $sSrc);
		if ($uid)
		{
			$this->oauth2_Api->bSaveUserToken($sSrc, $uid, $aTokeninfo);
			$this->baseinfoApi->bUpdateOauth2info($uid, $aUserinfo);
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
