<?php

require 'vendor/autoload.php';
require 'app/lib/ws.php';

// Database information
$settings = array(
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'giveastick',
    'username' => 'giveastick',
    'password' => 'giveastick',
    'collation' => 'utf8_general_ci',
    'prefix' => ''
);

// Bootstrap Eloquent ORM
$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());

require 'app/app.php';