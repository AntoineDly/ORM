<?php

namespace AntoineDly\ORM\Query;

final readonly class BindValueDto
{
    public function __construct(
        public string|int $param,
        public mixed $value,
        public PdoParamEnum $type
    )
    {
    }
}