<?php

class KRest_User_item
{
	public function post($update, $after = null)
	{
		if (!preg_match('/^[_0-9a-z]{4,16}$/i', $update['username']))
		{
			throw new Exception('登录名称只能使用字母，数字和下划线，4-16个字符', 1);
		}
		if (!preg_match('/^[_0-9a-z]{4,16}$/i', $update['passwd']))
		{
			throw new Exception('登录密码只能使用字母，数字和下划线，4-16个字符', 2);
		}

		if ('' == $update['nickname'])
		{
			$update['nickname'] = $update['username'];
		}

		$loginApi = new KUser_loginApi;
		$uid = $loginApi->iRegister($update['username'], $update['passwd'], $errno);
		if (!$uid)
		{
			if (Ko_Mode_User::E_REGISTER_ALREADY === $errno)
			{
				throw new Exception('登录名称已经被使用了', 3);
			}
			throw new Exception('注册失败', 4);
		}
		$baseinfoApi = new KUser_baseinfoApi;
		$baseinfoApi->bUpdateNickname($uid, $update['nickname']);
		$loginApi->vSetLoginUid($uid, 'reg');
		return array('key' => $uid);
	}
}
