<?php

class KRender_default extends Ko_View_Render_Smarty
{
	public function sRender()
	{
		$head = new Ko_View_Render_Smarty;
		$head->oSetTemplate('common/head.html');
		$head->oSetData('IMG_DOMAIN', IMG_DOMAIN);
		$tail = new Ko_View_Render_Smarty;
		$tail->oSetTemplate('common/tail.html');
		return $head->sRender().parent::sRender().$tail->sRender();
	}
}
