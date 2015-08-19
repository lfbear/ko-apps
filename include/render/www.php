<?php

class KRender_www extends KRender_base
{
	public function sRender()
	{
		$head = new Ko_View_Render_Smarty;
		$head->oSetTemplate('www/common/header.html')
			->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN)
			->oSetData('IMG_DOMAIN', IMG_DOMAIN);

		$tail = new Ko_View_Render_Smarty;
		$tail->oSetTemplate('www/common/footer.html');

		return $head->sRender().parent::sRender().$tail->sRender();
	}
}
