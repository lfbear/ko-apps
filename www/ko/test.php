<?php

Ko_Web_Route::VGet('plupload', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('ko/test/plupload.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('tinymce', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('ko/test/tinymce.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('push', function()
{
	Ko_Web_Response::VSetContentType('text/event-stream');
	Ko_Web_Response::VSend();
	for ($i=0; $i<2; $i++)
	{
		echo 'data:'.date('Y-m-d H:i:s')."\r\n";
		echo 'id:'.(1-$i)."\r\n";
		echo "\r\n";
		flush();
		sleep(1);
	}
	echo 'retry: 5000'."\r\n";
	echo "\r\n";
});

Ko_Web_Route::VGet('index', function()
{
	$render = new KRender_default;
	$render->oSetTemplate('ko/test/index.html');
	Ko_Web_Response::VAppendBody($render);
	Ko_Web_Response::VSend();
});

Ko_Web_Route::VGet('DB', function()
{
	$api = new KTest_Api;
	$api->test();
});

class KTest_Dao extends Ko_Dao_Factory
{
	protected $_aDaoConf = array(
		'test' => array(
			'type' => 'db_single',
			'kind' => 'test',
			'key' => array('id0', 'id1'),
		),
	);
}

class KTest_Api extends Ko_Busi_Api
{
	public function test()
	{
		$this->add(1, 'a');
		$this->add(1, 'b');
		$this->add(2, 'a');
		$this->add(2, 'b');

		$objs = array(
			array(
				'id0' => 2,
				'id1' => 'b',
			),
			array(
				'id0' => 1,
				'id1' => 'b',
			),
			array(
				'id0' => 1,
				'id1' => 'a',
			),
			array(
				'id0' => 2,
				'id1' => 'a',
			),
		);
		$ret = $this->testDao->aGetDetails($objs, '', '', true);
		var_dump($ret);
	}

	public function add($id0, $id1)
	{
		try {
			$data = compact('id0', 'id1');
			$this->testDao->aInsert($data);
		} catch (Exception $ex) {
		}
	}
}
