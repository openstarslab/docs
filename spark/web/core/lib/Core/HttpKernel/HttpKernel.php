<?php

/**
 * Copyright (C) 2023 OpenStars Lab Development Team
 *
 * This file is part of spark/spark
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace Spark\Core\HttpKernel;

use Nulldark\Container\ContainerInterface;
use Nulldark\Routing\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Spark\Core\Routing\CallableResolver;
use function is_callable;
use function sprintf;

/**
 * @package Spark\Core\HttpKernel
 * @since 0.1.0
 */
final class HttpKernel implements HttpKernelInterface
{
    private readonly CallableResolver $callableResolver;

    public function __construct(
        private readonly ContainerInterface $container
    )
    {
        $this->callableResolver = new CallableResolver();
    }


    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = $this->container->make(RouterInterface::class)->match($request);

        $callback = $this->callableResolver->resolve($route);

        if (is_callable($callback)) {
            $response = $callback();
        } else {
            $instance = $this->container->make($callback[0]);
            $response = $instance->{$callback[1]}();
        }


        if (!($response instanceof ResponseInterface)) {
            $msg = sprintf('The controller must return a "Psr\Http\Message\ResponseInterface".');

            if (null === $response) {
                $msg .= ' Did you forget to add a return statement somewhere in your controller?';
            }

            throw new RuntimeException($msg);
        }

        return $response;
    }
}
