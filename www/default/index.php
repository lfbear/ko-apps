<?php

$num = 10;
$sysmsgApi = new KSysmsg_Api;
$msglist = $sysmsgApi->getIndexList('0_0', $num, $next, $next_boundary);

$page = compact('num', 'next', 'next_boundary');
$render = new KRender_default;
$render->oSetTemplate('default/index.html')
	->oSetData('msglist', $msglist)
	->oSetData('page', $page)
	->oSend();
