<?php

declare(strict_types=1);

/*
 * This file is part of the AntoineDly/Router package.
 *
 * (c) Antoine Delaunay <antoine.delaunay333@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AntoineDly\ORM\Entity;

use Exception;

trait EntityTrait
{
    public static function getTable(): string
    {
        if (!str_contains(static::class, 'Entity')) {
            throw new Exception(message: static::class . 'is not an Entity');
        }
        return strtolower(str_replace('Entity', '', static::class));
    }
}
