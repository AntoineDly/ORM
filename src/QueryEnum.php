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

namespace AntoineDly\ORM;

final class QueryEnum
{
    public const SELECT = 'SELECT';
    public const UPDATE = 'UPDATE';
    public const INSERT = 'INSERT';
    public const DELETE = 'DELETE';

    public const QUERY_TYPES = [
        self::SELECT,
        self::UPDATE,
        self::INSERT,
        self::DELETE
    ];
}
