<?php

    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader =  require_once  __DIR__ . '/../../autoload.php';
    $loader->add('Spark\\Tests', __DIR__);

    $suites = [
        'Spark\\Tests\\Unit\\' => [__DIR__ . '/Unit'],
        'Spark\\Tests\\Stub\\' => [__DIR__ . '/Stub']
    ];

    foreach ($suites as $prefix => $paths) {
        $loader->addPsr4($prefix, $paths);
    }

    setlocale(LC_ALL, 'C');

    mb_internal_encoding('utf-8');
    mb_language('uni');
