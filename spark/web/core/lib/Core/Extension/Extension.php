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

namespace Spark\Core\Extension;

use Nulldark\Container\ContainerInterface;

/**
 * @package Spark\Core\Extension
 * @version 0.1.0
 */
abstract class Extension
{
    /** @var ContainerInterface $container */
    protected ContainerInterface $container;

    public function __construct(
        protected readonly string $name,
        protected readonly string $version,
        protected readonly string $path
    )
    {
    }

    /**
     * Gets an extension name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets an extension version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Gets an extension path.
     *
     * @return string
     */
    public function getExtensionPath(): string
    {
        return $this->path;
    }

    /**
     * Sets container instance.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Sets container instance.
     *
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Boots an extension.
     *
     * @return void
     */
    abstract public function boot(): void;
}
