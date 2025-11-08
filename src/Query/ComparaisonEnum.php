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

use AntoineDly\ORM\Exceptions\ComparaisonEnumException;

enum ComparaisonEnum: string
{
    case FIRST = 'FIRST';
    case AND = 'AND';
    case OR = 'OR';

    public function getSQL(): string
    {
        return match ($this) {
            self::FIRST => '',
            self::AND => 'AND',
            self::OR => 'OR',
            default => throw new ComparaisonEnumException('Undefined comparaison : '.$this->value)
        };
    }
}
