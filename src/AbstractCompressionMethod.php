<?php

/**
 * AbstractCompressionMethod.php
 *
 * Copyright 2020 Danny Damsky
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package coffeephp\compression-method
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-09-09
 */

declare(strict_types=1);

namespace CoffeePhp\CompressionMethod;

use CoffeePhp\FileSystem\Contract\Data\Path\PathNavigatorInterface;
use CoffeePhp\FileSystem\Contract\FileManagerInterface;
use CoffeePhp\FileSystem\Data\Path\PathNavigator;
use CoffeePhp\FileSystem\Enum\PathConflictStrategy;

use function str_ends_with;
use function strlen;
use function substr;

/**
 * Class AbstractCompressionMethod
 * @package coffeephp\compression-method
 * @author Danny Damsky <dannydamsky99@gmail.com>
 * @since 2020-09-09
 */
abstract class AbstractCompressionMethod
{
    protected FileManagerInterface $fileManager;
    private PathConflictStrategy $pathConflictStrategy;

    /**
     * AbstractPathCompressionMethod constructor.
     * @param FileManagerInterface $fileManager
     * @param PathConflictStrategy|null $pathConflictStrategy
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(
        FileManagerInterface $fileManager,
        ?PathConflictStrategy $pathConflictStrategy = null
    ) {
        $this->fileManager = $fileManager;
        $this->pathConflictStrategy = $pathConflictStrategy ?? PathConflictStrategy::WORKAROUND();
    }

    /**
     * Create an empty file and delete it before
     * returning, this will give us an absolute path
     * that always exists.
     *
     * @param string $path
     * @return PathNavigatorInterface
     */
    final protected function getAvailablePath(string $path): PathNavigatorInterface
    {
        $availableFile = $this->fileManager->createFile(
            new PathNavigator($path),
            $this->pathConflictStrategy
        );
        $availableFile->delete();
        return $availableFile->getPath();
    }

    /**
     * Get the full path.
     *
     * @param string $path
     * @param string $extension
     * @return string
     */
    final protected function getFullPath(string $path, string $extension): string
    {
        return "{$path}{$this->getExtensionSuffix($extension)}";
    }

    /**
     * Get whether the path ends with the given extension.
     *
     * @param string $path
     * @param string $extension
     * @return bool
     */
    final protected function isFullPath(string $path, string $extension): bool
    {
        return str_ends_with($path, $this->getExtensionSuffix($extension));
    }

    /**
     * Get the original path (without the suffix).
     *
     * @param string $path
     * @param string $extension
     * @return string
     */
    final protected function getOriginalPath(string $path, string $extension): string
    {
        return substr($path, 0, -strlen($this->getExtensionSuffix($extension)));
    }

    /**
     * Get the suffix for the given extension.
     *
     * @param string $extension
     * @return string
     */
    final protected function getExtensionSuffix(string $extension): string
    {
        return ".{$extension}";
    }
}
