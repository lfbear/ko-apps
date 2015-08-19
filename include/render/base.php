<?php

class KRender_base extends Ko_View_Render_Smarty
{
	public function oSend()
	{
		Ko_Web_Response::VAppendBody($this);
		Ko_Web_Response::VSend();
		return $this;
	}
}