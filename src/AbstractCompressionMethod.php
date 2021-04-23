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

use function file_exists;
use function pathinfo;
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
    /**
     * A workaround mechanism for retrieving an available path
     * with a file/directory name similar to the given $expectedDestination
     */
    final protected function getAvailablePath(string $expectedDestination): string
    {
        if (!file_exists($expectedDestination)) {
            return $expectedDestination;
        }
        $pathInfo = pathinfo($expectedDestination);
        $filename = $pathInfo['filename'];
        $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
        $index = 0;
        do {
            ++$index;
            $availablePath = "{$filename}_{$index}{$extension}";
        } while (file_exists($availablePath));
        return $availablePath;
    }

    /**
     * Get the full path.
     */
    final protected function getFullPath(string $path, string $extension): string
    {
        return "$path.$extension";
    }

    /**
     * Get whether the path ends with the given extension.
     */
    final protected function isFullPath(string $path, string $extension): bool
    {
        return str_ends_with($path, ".$extension");
    }

    /**
     * Get the original path (without the suffix).
     */
    final protected function getOriginalPath(string $path, string $extension): string
    {
        return substr($path, 0, -strlen(".$extension"));
    }

    /**
     * Get the suffix for the given extension.
     */
    final protected function getExtensionSuffix(string $extension): string
    {
        return ".$extension";
    }
}
