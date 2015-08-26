<?php

class KRender_json extends Ko_View_Render_JSON
{
	public function oSend()
	{
		Ko_Web_Response::VSend($this);
		return $this;
	}
}
