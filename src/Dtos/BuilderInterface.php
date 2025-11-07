<?php

declare(strict_types=1);

namespace AntoineDly\ORM\Dtos;

interface BuilderInterface
{
    public function build(): DtoInterface;
}
