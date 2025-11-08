<?php

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Exceptions\JoinTypeEnumException;

enum JoinTypeEnum: string
{
    case JOIN = 'JOIN';
    case LEFT_JOIN = 'LEFT_JOIN';
    case RIGHT_JOIN = 'RIGHT_JOIN';
    case INNER_JOIN = 'INNER_JOIN';
    case FULL_JOIN = 'FULL_JOIN';
    case CROSS_JOIN = 'CROSS_JOIN';

    public function getSQL(): string
    {
        return match ($this) {
            self::JOIN => ' JOIN ',
            self::LEFT_JOIN => ' LEFT_JOIN ',
            self::RIGHT_JOIN => ' RIGHT_JOIN ',
            self::INNER_JOIN => ' INNER_JOIN ',
            self::FULL_JOIN => ' FULL_JOIN ',
            self::CROSS_JOIN => ' CROSS_JOIN ',
            default => throw new JoinTypeEnumException('Unexpected join type : '.$this->value)
        };
    }
}
