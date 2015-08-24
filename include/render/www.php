<?php

class KRender_www extends KRender_base
{
	public function sRender()
	{
		$loginApi = new KUser_loginApi;
		$uid = $loginApi->iGetLoginUid();
		$binfo = $uid ? Ko_Tool_Adapter::VConv($uid, array('user_baseinfo', array('logo32'))) : array();

		$head = new Ko_View_Render_Smarty;
		$head->oSetTemplate('www/common/header.html')
			->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN)
			->oSetData('IMG_DOMAIN', IMG_DOMAIN)
			->oSetData('baseinfo', $binfo);

		$tail = new Ko_View_Render_Smarty;
		$tail->oSetTemplate('www/common/footer.html');

		return $head->sRender().parent::sRender().$tail->sRender();
	}
}
