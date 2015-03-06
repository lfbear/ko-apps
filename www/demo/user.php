<?php

Ko_Web_Route::VGet('test', function()
{
	$ret = call_user_func_array(array('KUser_loginFacade', 'iLogin'), array('zhangchu', 'zhangchu', &$iErrno));
	var_dump($ret);
	var_dump($iErrno);
});

Ko_Web_Route::VGet('regist', function()
{
	$head = new Ko_View_Render_Smarty;
	$head->oSetTemplate('common/head.html');
	Ko_Web_Response::VAppendBody($head);

	$render = new Ko_View_Render_Smarty;
	$render->oSetTemplate('user/regist.html');
	Ko_Web_Response::VAppendBody($render);

	$head = new Ko_View_Render_Smarty;
	$head->oSetTemplate('common/tail.html');
	Ko_Web_Response::VAppendBody($head);

	Ko_Web_Response::VSend();
});
