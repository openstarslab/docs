<?php

$app = require __DIR__ . '/../app/app.php';
$app->run(
    \Spark\Http\Request::fromGlobals()
);