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

use FilesystemIterator;
use Nulldark\Stdlib\Collections\Collection;
use Nulldark\Stdlib\Collections\CollectionInterface;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Spark\Core\Extension\Discovery\RecursiveCallbackFilter;
use SplFileInfo;

/**
 * @package Spark\Core\Extension
 * @since 0.1.0
 */
final readonly class ExtensionDiscovery
{
    /**
     * Returns a collection with matching extensions files (composer.json).
     *
     * @param string $directory
     * @return CollectionInterface
     */
    public function scanDirectory(string $directory): CollectionInterface
    {
        $flags = FilesystemIterator::UNIX_PATHS;
        $flags |= FilesystemIterator::SKIP_DOTS;
        $flags |= FilesystemIterator::FOLLOW_SYMLINKS;
        $flags |= FilesystemIterator::CURRENT_AS_SELF;

        $directoryIterator = new RecursiveDirectoryIterator($directory, $flags);

        $callback = new RecursiveCallbackFilter();
        $filter = new RecursiveCallbackFilterIterator($directoryIterator, [$callback, 'accept']);

        $iterator = new RecursiveIteratorIterator(
            $filter,
            RecursiveIteratorIterator::LEAVES_ONLY,
            RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        $files = [];

        /** @var SplFileInfo $value */
        foreach ($iterator as $value) {
            $files[] = [
                'path' => $value->getPath(),
                'pathname' => $value->getPathname()
            ];
        }

        return new Collection($files);
    }
}
