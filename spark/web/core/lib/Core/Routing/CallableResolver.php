<?php

namespace Spark\Core\Routing;

use Nulldark\Routing\Route;
use function is_string;

class CallableResolver
{
    public function resolve(Route $route): callable|array
    {
        $callback = $route->callback();

        if (is_string($callback)) {
            $parts = explode('::', $callback);

            $controller = array_shift($parts);
            $method = array_shift($parts);

            return [$controller, $method];
        }

        return $callback;
    }
}
