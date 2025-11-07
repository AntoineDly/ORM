<?php

namespace AntoineDly\ORM\Query;

enum OperatorEnum: string
{
    case SUPERIOR = '>';
    case SUPERIOR_OR_EQUAL = '>=';
    case EQUAL = '=';
    case INFERIOR = '<';
    case INFERIOR_OR_EQUAL = '<=';
    case IN = 'IN';

}
