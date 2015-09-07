<?php

class KRest_Sysmsg_item
{
	public static $s_aConf = array(
		'unique' => array('hash', array(
			'uid' => 'int',
			'msgid' => 'int',
		)),
		'stylelist' => array(
			'default' => array('hash', array(
				'uid' => 'int',
				'msgid' => 'int',
				'msgtype' => 'int',
				'stime' => 'int',
				'content' => 'any',
				'ctime' => 'string',
			)),
		),
		'filterstylelist' => array(
			'default' => 'any',
		),
	);

	public function getMulti($style, $page, $filter, $exstyle = null, $filter_style = 'default')
	{
		$num = $page['num'];
		$sysmsgApi = new KSysmsg_Api;
		$msglist = $sysmsgApi->getIndexList($page['boundary'], $num, $next, $next_boundary);
		$page = compact('num', 'next', 'next_boundary');
		return array(
			'list' => $msglist,
			'page' => $page,
		);
	}
}
