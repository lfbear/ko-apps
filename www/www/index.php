<?php

$baseinfoApi = new KUser_baseinfoApi();
$allusers = $baseinfoApi->aGetAllUser(array('logo48'));

$sysmsgApi = new KSysmsg_Api;
$msglist = $sysmsgApi->aGetList(0, 'index', 0, 10);

$render = new KRender_www;
$render->oSetTemplate('www/index.html')
	->oSetData('allusers', $allusers)
	->oSetData('msglist', $msglist)
	->oSend();
