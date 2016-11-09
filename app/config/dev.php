<?php

// Doctrine (db)
$app['db.options'] = array(
    'dbname' => 'diabloodwebsite',
    'user' => 'diablood',
    'password' => 'password',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
  	'charset'  => 'utf8',
);

// enable the debug mode
$app['debug'] = true;
