<?php

namespace Spark\Modules\Dashboard;

use Nulldark\Container\ContainerInterface;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use Spark\Extension\Extension;
use Spark\Extension\ModuleInterface;

class Dashboard extends Extension implements ModuleInterface
{
    /**
     * @inheritDoc
     */
    public function boot(ContainerInterface $container): void
    {
        $container->get('router')->get('/', function () {
            return new Response(200, [], Stream::create('hello from "' . self::class . '"'));
        });
    }
}
