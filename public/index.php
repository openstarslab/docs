<?php

$app = require_once __DIR__ . '/../app/app.php';

$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$serverRequestCreator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$app->handle(
    $request = $serverRequestCreator->fromGlobals()
);
