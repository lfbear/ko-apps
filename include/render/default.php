<?php

class KRender_default extends Ko_View_Render_Smarty
{
	public function sRender()
	{
		$baseinfoApi = new KUser_baseinfoApi;
		$binfo = $baseinfoApi->aGetMoreInfo();
		
		$head = new Ko_View_Render_Smarty;
		$head->oSetTemplate('common/head.html')
			->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN)
			->oSetData('IMG_DOMAIN', IMG_DOMAIN)
			->oSetData('baseinfo', $binfo);

		$tail = new Ko_View_Render_Smarty;
		$tail->oSetTemplate('common/tail.html');

		return $head->sRender().parent::sRender().$tail->sRender();
	}
}
