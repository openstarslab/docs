<?php

namespace Spark\Dashboard\Controller;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Spark\Dashboard\DashboardExtension;

class IndexController
{
    public function index(): ResponseInterface
    {
        return new \Laminas\Diactoros\Response\HtmlResponse(DashboardExtension::class);
    }
}
