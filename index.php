<?php

/* 定义这个常量是为了在application.ini中引用*/
define('APPLICATION_PATH', dirname(__FILE__));
require APPLICATION_PATH . '/application/vendor/autoload.php';
$application = new Yaf\Application(APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
