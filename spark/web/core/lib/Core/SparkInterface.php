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

namespace Spark\Core;

use Nulldark\Container\ContainerInterface;
use Spark\Core\Support\ServiceProvider;

/**
 * @package Spark\Core
 * @version 0.1.0
 */
interface SparkInterface extends ContainerInterface
{
    /**
     * Determine if the application is booted.
     *
     * @return bool
     */
    public function isBooted(): bool;

    /**
     * Registers a service provider with the application.
     *
     * @param ServiceProvider $provider
     * @return ServiceProvider
     */
    public function register(ServiceProvider $provider): ServiceProvider;

    /**
     * Sets given service provider as registered.
     *
     * @param ServiceProvider $provider
     * @return void
     */
    public function setProviderAsRegistered(ServiceProvider $provider): void;

    /**
     * Gets the registered service provider instance if not exists returns `NULL`.
     *
     * @param ServiceProvider $provider
     * @return ServiceProvider|null
     */
    public function getProvider(ServiceProvider $provider): ServiceProvider|null;

    /**
     * Gets the path
     *
     * @param string $path
     * @return string
     */
    public function path(string $path = ''): string;
}
