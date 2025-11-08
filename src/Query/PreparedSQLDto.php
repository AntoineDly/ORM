<?php

namespace AntoineDly\ORM\Query;

final readonly class PreparedSQLDto
{
    public function __construct(
        public string $sql,
        public BindValueDtoCollection $bindValues
    ) {
    }
}
