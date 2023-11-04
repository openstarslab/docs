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

use Composer\Autoload\ClassLoader;
use LogicException;
use RuntimeException;
use SplFileInfo;

/**
 * @package Spark\Core\Extension
 * @since  0.1.0
 *
 * @phpstan-type ExtensionInfos = array{
 *     'name': string,
 *     'version': string,
 *     'path': string,
 *     'baseClass': string,
 *     'autoload': array<string, array<string, string|string[]>>
 * }
 */
final class ExtensionLoader
{
    /** @var bool $initialized */
    private bool $initialized = false;

    /** @var list<ExtensionInfos> $extensionInfos */
    private array $extensionInfos = [];

    /** @var array<string, Extension> $extensions */
    private array $extensions;

    public function __construct(
        protected readonly string $projectDir,
        protected readonly string $extensionDir,
        protected readonly ClassLoader $classLoader
    ) {
    }

    /**
     * Gets all loaded extensions.
     *
     * @return array<string, Extension>
     */
    public function getExtensions(): array
    {
        if ($this->initialized !== true) {
            $this->doScanExtensions();
        }

        return $this->extensions;
    }

    /**
     * Gets an `ExtensionDiscovery` instance.
     *
     * @return ExtensionDiscovery
     */
    public function getExtensionDiscovery(): ExtensionDiscovery
    {
        return new ExtensionDiscovery();
    }

    /**
     * Loads all extensions from filesystem.
     *
     * @internal
     * @return void
     */
    private function doScanExtensions(): void
    {
        $this->getExtensionDiscovery()
            ->scanDirectory($this->extensionDir)
            ->each(function ($extension) {
                if (!\is_array($extension)) {
                    return;
                }

                /** @var array{path: string, pathname: string} $extension */
                $this->loadExtensionInfos($extension);
            });

        $this->registerNamespaces();
        $this->initializeExtensions();

        $this->initialized = true;
    }

    /**
     * Initializes extensions.
     *
     * @return void
     *
     * @throws LogicException
     */
    private function initializeExtensions(): void
    {
        $this->extensions = [];

        foreach ($this->registerExtensions() as $extension) {
            $name = $extension->getName();

            if (isset($this->extensions[$name])) {
                throw new LogicException(sprintf(
                    'Trying to register two extensions with the same name "%s"',
                    $name
                ));
            }

            $this->extensions[$name] = $extension;
        }
    }

    /**
     * Gets an extensions instance.
     *
     * @return iterable<Extension>
     */
    private function registerExtensions(): iterable
    {
        foreach ($this->extensionInfos as $extensionInfo) {
            /** @var Extension $class */
            $class = $extensionInfo['baseClass'];

            yield new $class(
                $extensionInfo['name'],
                $extensionInfo['version'],
                $extensionInfo['path']
            );
        }
    }

    /**
     * Loads information about an extension.
     *
     * @param array{path: string, pathname: string} $extension
     *
     * @return void
     */
    private function loadExtensionInfos(array $extension): void
    {
        $content = file_get_contents($extension['pathname']);
        assert(is_string($content));

        $json = json_decode($content, true);
        assert(is_array($json));

        if (isset($json['extra'])) {
            $extra = $json['extra'];

            $this->extensionInfos[] = [
                'name' => $extra['name'],
                'version' => $extra['version'],
                'path' => $extension['path'],
                'baseClass' => $extra['baseClass'],
                'autoload' => $json['autoload']
            ];
        }
    }

    /**
     * Registers extension namespaces into composer class loader.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    private function registerNamespaces(): void
    {
        foreach ($this->extensionInfos as $extension) {
            \assert(\is_string($extension['baseClass']));
            $extensionName = $extension['name'] ?? $extension['baseClass'];

            if (!isset($extension['autoload'])) {
                throw new \RuntimeException(sprintf(
                    'Unable to register extension "%s" in autoload. Required property `autoload` missing.',
                    $extensionName
                ));
            }

            $psr4 = $extension['autoload']['psr-4'] ?? [];

            if (\count($psr4) === 0) {
                throw new \RuntimeException(sprintf(
                    'Unable to register extension "%s" in autoload. Required property `psr-4` missing.',
                    $extensionName
                ));
            }

            /**
             * @var string $namespace
             * @var list<string>|string $paths
             */
            foreach ($psr4 as $namespace => $paths) {
                if (\is_string($paths)) {
                    $paths = [$paths];
                }

                $mappedPaths = $this->autoloadPathMaps($extensionName, $paths, $extension['path']);
                $this->classLoader->addPsr4($namespace, $mappedPaths);
                if ($this->classLoader->isClassMapAuthoritative()) {
                    $this->classLoader->setClassMapAuthoritative(false);
                }
            }
        }
    }

    /**
     * Prepares given paths to composer autoload.
     *
     * @param string $extension
     * @param string[] $paths
     * @param string $extensionPath
     *
     * @return list<string>
     *
     * @throws RuntimeException
     */
    private function autoloadPathMaps(string $extension, array $paths, string $extensionPath): array
    {
        if (mb_strpos($extensionPath, $this->projectDir) !== 0) {
            throw new RuntimeException(
                sprintf(
                    'Extension dir %s needs to be a sub-directory of the project dir %s',
                    $extension,
                    $this->projectDir
                )
            );
        }

        $mapped = [];
        foreach ($paths as $path) {
            $mapped[] = $extensionPath . DIRECTORY_SEPARATOR . $path;
        }

        return $mapped;
    }
}
