<?php

namespace Spark\Dashboard;

use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use Nulldark\Routing\RouterInterface;
use Spark\Core\Extension\Extension;
use Spark\Dashboard\Controller\IndexController;

final class DashboardExtension extends Extension
{
    public function boot(): void
    {
        $this->container->get(RouterInterface::class)->get('/', "\Spark\Dashboard\Controller\IndexController::index");
    }
}
