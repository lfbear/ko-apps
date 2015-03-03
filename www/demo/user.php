<?php

Ko_Web_Route::VGet('test', function()
{
	call_user_func_array(array('KUser_loginFacade', 'iLogin'), array('zhangchu', 'zhangchu', &$iErrno));
});
