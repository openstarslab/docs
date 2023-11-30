<?php
    require __DIR__ . '/../vendor/autoload.php';

    $app = new \Spark\Core\Application();
    $app->setProjectDir(\dirname(__DIR__));

    return $app;