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

use Nulldark\Container\Container;

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
    private bool $booted;

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

    private function initializeSettings(): void
    {
        \error_reporting(E_STRICT | E_ALL);

        // Set sane locale settings, to ensure consistent.
        \setlocale(\LC_ALL, 'C');

        // Sets configuration for multi-byte strings.
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
