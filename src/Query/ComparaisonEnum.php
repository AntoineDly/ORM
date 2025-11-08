<?php

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
