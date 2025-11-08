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

use AntoineDly\ORM\Exceptions\OperatorEnumException;

enum OperatorEnum: string
{
    case SUPERIOR = 'SUPERIOR';
    case SUPERIOR_OR_EQUAL = 'SUPERIOR_OR_EQUAL';
    case EQUAL = 'EQUAL';
    case INFERIOR = 'INFERIOR';
    case INFERIOR_OR_EQUAL = 'INFERIOR_OR_EQUAL';
    case IN = 'IN';

    public function getSQL(): string
    {
        return match ($this) {
            self::SUPERIOR => '>',
            self::SUPERIOR_OR_EQUAL => '>=',
            self::EQUAL => '=',
            self::INFERIOR => '<',
            self::INFERIOR_OR_EQUAL => '<=',
            self::IN => 'IN',
            default => throw new OperatorEnumException('Undefined operator : '.$this->value)
        };
    }

}
