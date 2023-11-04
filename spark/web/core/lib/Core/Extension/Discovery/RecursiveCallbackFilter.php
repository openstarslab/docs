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

namespace Spark\Core\Extension\Discovery;

use RecursiveDirectoryIterator;

/**
 * @package Spark\Core\Extention\Discovery;
 * @version 0.1.0
 *
 * @internal
 */
class RecursiveCallbackFilter
{
    /**
     * Checks if given directory are acceptable.
     *
     * @param RecursiveDirectoryIterator $directory
     * @return bool `TRUE` if the `$directory` is acceptable, otherwise FALSE.
     */
    public function accept(RecursiveDirectoryIterator $directory): bool
    {
        $name = $directory->getFilename();

        if (str_starts_with(".", $name)) {
            return false;
        }

        if ($directory->isDir()) {
            return true;
        }

        return str_ends_with($name, 'composer.json');
    }
}
