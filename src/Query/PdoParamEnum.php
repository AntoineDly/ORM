<?php

declare(strict_types=1);

/*
 * This file is part of the AntoineDly/ORM package.
 *
 * (c) Antoine Delaunay <antoine.delaunay333@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Exceptions\SQLDirectionException;
use PDO;

enum PdoParamEnum: string
{
    case INTEGER = 'integer';
    case INT = 'int';
    case FLOAT = 'float';
    case BOOLEAN = 'boolean';
    case BOOL = 'bool';
    case STRING = 'string';
    case TEXT = 'text';

    public function getPdoType(): int
    {
        return match ($this) {
            self::INTEGER, self::INT => PDO::PARAM_INT,
            self::FLOAT, self::STRING, self::TEXT => PDO::PARAM_STR,
            self::BOOLEAN, self::BOOL => PDO::PARAM_BOOL,
            default => throw new SQLDirectionException('This PDO type doesn\'t exist : ' . $this->value)
        };
    }
}
