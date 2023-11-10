<?php

namespace Spark\Core\Routing;

use Nulldark\Routing\RouteMatch;

class CallableResolver
{
    public function resolve(RouteMatch $route): callable|false
    {
        if (!$handler = $route->getDefault('_handler')) {
            return false;
        }

        if (\is_array($handler)) {
            if (isset($handler[0]) && isset($handler[1])) {
                try {
                    $handler[0] = new $handler[0]();
                } catch (\Error | \LogicException $e) {
                    if (\is_callable($handler)) {
                        return $handler;
                    }

                    throw $e;
                }
            }
        }

        return $handler;
    }
}
