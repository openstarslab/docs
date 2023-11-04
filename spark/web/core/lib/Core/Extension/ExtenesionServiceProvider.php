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

use Spark\Core\Support\ServiceProvider;

/**
 * @package Spark\Core\Extenesion
 * @since 0.1.0
 */
class ExtenesionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->spark->singleton(ExtensionLoader::class, ExtensionLoader::class);
    }

    public function boot(): void
    {
        /** @var ExtensionLoader $loader */
        $loader = $this->spark->get(ExtensionLoader::class);
        $extensions = $loader->getExtensions();

        array_walk($extensions, function (Extension $extension) {
                $extension->setContainer($this->spark);
                $extension->boot();
        });
    }
}
