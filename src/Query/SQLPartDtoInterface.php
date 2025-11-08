<?php

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Dtos\DtoInterface;

interface SQLPartDtoInterface extends DtoInterface
{
    public function getSQl(): string;

    public function getBindValues(): BindValueDtoCollection;
}
