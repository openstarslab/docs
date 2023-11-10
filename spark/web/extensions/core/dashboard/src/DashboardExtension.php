<?php

namespace Spark\Dashboard;

use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use Spark\Core\Extension\Extension;
use Spark\Dashboard\Controller\IndexController;

final class DashboardExtension extends Extension
{
    public function boot(): void
    {
        $route = new Route("/", [
            '_handler' => [IndexController::class, 'index']
        ], ["GET"]);

        $this->container->get(RouteCollection::class)
            ->add('dashboard.index', $route);
    }
}
