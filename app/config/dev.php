<?php

// Doctrine (db)
$app['db.options'] = array(
    'dbname' => 'diablood',
    'user' => 'diablood',
    'password' => 'password',
    'host' => '127.0.0.1',
    'driver' => 'pdo_mysql',
  	'charset'  => 'utf8',
);

// enable the debug mode
$app['debug'] = true;
