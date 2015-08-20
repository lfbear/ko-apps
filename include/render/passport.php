<?php

class KRender_passport extends KRender_base
{
	public function sRender()
	{
		$head = new Ko_View_Render_Smarty;
		$head->oSetTemplate('passport/common/header.html')
			->oSetData('PASSPORT_DOMAIN', PASSPORT_DOMAIN)
			->oSetData('IMG_DOMAIN', IMG_DOMAIN)
			->oSetData('WWW_DOMAIN', WWW_DOMAIN);

		$tail = new Ko_View_Render_Smarty;
		$tail->oSetTemplate('passport/common/footer.html');

		return $head->sRender().parent::sRender().$tail->sRender();
	}
}
