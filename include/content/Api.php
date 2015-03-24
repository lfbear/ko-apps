<?php

class KContent_Api extends Ko_Mode_Content
{
	const USER_DRAFT = 1;
	const UUID_DRAFT = 2;
	
	protected $_aConf = array(
		'contentApi' => 'Func',
		'app' => array(
			self::USER_DRAFT => array(
				'type' => 'html',
			),
			self::UUID_DRAFT => array(
				'type' => 'html',
			),
		),
	);
	
	public function bSet($iAid, $iId, $sContent)
	{
		if (isset($this->_aConf['app'][$iAid]) && 'html' === $this->_aConf['app'][$iAid]['type'])
		{
			$sContent = $this->_sReplaceDataUrl($sContent, '"');
			$sContent = $this->_sReplaceDataUrl($sContent, "'");
		}
		return parent::bSet($iAid, $iId, $sContent);
	}
	
	private function _sReplaceDataUrl($sContent, $quote)
	{
		while (1)
		{
			$pos1 = stripos($sContent, 'src='.$quote.'data:');
			if (false === $pos1)
			{
				break;
			}
			$pos2 = strpos($sContent, $quote, $pos1 + 10);
			if (false !== $pos2)
			{
				$dataurl = substr($sContent, $pos1 + 5, $pos2 - $pos1 - 5);
			}
			else
			{
				$dataurl = substr($sContent, $pos1 + 5);
			}
			$data = file_get_contents($dataurl);
			$api = new KStorage_Api;
			if (false !== $data && $api->bContent2Storage($data, $sDest))
			{
				$url = $api->sGetUrl($sDest, 'imageView2/2/w/600/h/600');
			}
			else
			{
				$url = '';
			}
			$sContent = substr($sContent, 0, $pos1 + 5).$url.substr($sContent, $pos2);
		}
		return $sContent;
	}
}
