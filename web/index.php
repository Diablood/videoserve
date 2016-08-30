<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add('SilexApi',__DIR__.'/../src');

$app = new Silex\Application();

require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';

$app->run();
