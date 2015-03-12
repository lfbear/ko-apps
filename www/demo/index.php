<?php

$render = new KRender_default;
$render->oSetTemplate('index.html');
Ko_Web_Response::VAppendBody($render);
Ko_Web_Response::VSend();
