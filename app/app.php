<?php

    $autoload = require_once __DIR__ . '/../vendor/autoload.php';

    $app = new \Spark\Foundation\Spark(__DIR__, $autoload);

    return $app;
