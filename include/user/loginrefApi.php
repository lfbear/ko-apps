<?php

class KUser_loginrefApi
{
	const COOKIE_NAME = 'ref';
	
	public static function VInit()
	{
		$referer = Ko_Web_Request::SHttpReferer();
		if ('' != $referer)
		{
			$rinfo = parse_url($referer);
			if (PASSPORT_DOMAIN !== $rinfo['host'])
			{
				Ko_Web_Response::VSetCookie(self::COOKIE_NAME, $referer);
			}
		}
	}
	
	public static function SGet()
	{
		$ref = Ko_Web_Request::SCookie(self::COOKIE_NAME);
		if ('' == $ref)
		{
			return 'http://'.MAIN_DOMAIN;
		}
		return $ref;
	}
}
