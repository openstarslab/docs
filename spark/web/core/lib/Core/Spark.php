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

use Composer\Autoload\ClassLoader;
use Nulldark\Container\Container;
use Spark\Core\Extension\ExtenesionServiceProvider;
use Spark\Core\Routing\RoutingServiceProvider;
use Spark\Core\Support\ServiceProvider;

/**
 * @package Spark\Core
 * @version 0.1.0
 */
final class Spark extends Container implements SparkInterface
{
    /**
     * Indicates if the application has "booted".
     *
     * @var bool $booted
     */
    private bool $booted = false;

    /**
     * All registered service providers.
     *
     * @var array<string, ServiceProvider> $serviceProviders
     */
    private array $serviceProviders = [];

    /**
     * The base path for Spark instance.
     *
     * @var string $basePath
     */
    private string $basePath;

    /**
     * The composer class loader.
     *
     * @var ?ClassLoader $classLoader
     */
    private ?ClassLoader $classLoader = null;

    /**
     * @param string $basePath
     * @return void
     */
    public function __construct(string $basePath = '')
    {
        $this->setBasePath($basePath);

        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();

        parent::__construct();
    }

    /**
     * Sets the base path for the Spark.
     *
     * @param string $basePath
     * @return self
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = rtrim($basePath, '\/');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function path(string $path = ''): string
    {
        return $this->basePath . ($path != '' ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }

    /**
     * @inheritDoc
     */
    public function register(ServiceProvider $provider): ServiceProvider
    {
        if (($registered = $this->getProvider($provider)) !== null) {
            return $registered;
        }

        $provider->register();

        $this->setProviderAsRegistered($provider);

        if ($this->isBooted()) {
            $this->bootProvider($provider);
        }
        return $provider;
    }

    /**
     * Sets given service provider as registered.
     *
     * @param ServiceProvider $provider
     * @return void
     */
    public function setProviderAsRegistered(ServiceProvider $provider): void
    {
        $this->serviceProviders[get_class($provider)] = $provider;
    }

    /**
     * Gets the registered service provider instance if not exists returns `NULL`.
     *
     * @param ServiceProvider $provider
     * @return ServiceProvider|null
     */
    public function getProvider(ServiceProvider $provider): ServiceProvider|null
    {
        if (array_key_exists(get_class($provider), $this->serviceProviders)) {
            return $this->serviceProviders[get_class($provider)];
        }

        return null;
    }

    /**
     * Boots a service provider.
     *
     * @param ServiceProvider $provider
     * @return void
     */
    public function bootProvider(ServiceProvider $provider): void
    {
        if (method_exists($provider, 'boot')) {
            $provider->boot();
        }
    }

    /**
     * Boots an application.
     *
     * @return self
     */
    public function boot(): self
    {
        if ($this->isBooted()) {
            return $this;
        }

        $this->initializeSettings();

        array_walk($this->serviceProviders, function (ServiceProvider $provider) {
            $this->bootProvider($provider);
        });

        $this->booted = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    private function registerBaseBindings(): void
    {
        // set instances into container
        $this->singleton(Spark::class, $this);
        $this->singleton(Container::class, $this);
        $this->singleton(ClassLoader::class, $this->getClassLoader());

        $this->bind(SparkInterface::class, Spark::class, true);
    }

    /**
     * Gets a class loader instance.
     *
     * @return ClassLoader
     */
    public function getClassLoader(): ClassLoader
    {
        if ($this->classLoader !== null) {
            return $this->classLoader;
        }

        return $this->classLoader = require $this->path('autoload.php');
    }

    private function registerBaseServiceProviders(): void
    {
        $this->register(new RoutingServiceProvider($this));
        $this->register(new ExtenesionServiceProvider($this));
    }

    private function initializeSettings(): void
    {
        \error_reporting(E_STRICT | E_ALL);

        // Set correct locale settings, to ensure consistent.
        \setlocale(\LC_ALL, 'C');

        // Sets configuration for multibyte strings.
        \mb_internal_encoding('utf8');
        \mb_language('uni');

        if (\PHP_SAPI !== 'cli') {
            \ini_set('session.use_cookies', '1');
            \ini_set('session.use_only_cookies', '1');
            \ini_set('session.cache_limiter', '');
            \ini_set('session.cookie_httponly', '1');
        }
    }
}
