<?php

Ko_Web_Route::VGet('test', function()
{
	$ret = call_user_func_array(array('KUser_loginFacade', 'iLogin'), array('zhangchu', 'zhangchu', &$iErrno));
	var_dump($ret);
	var_dump($iErrno);
});
