<?php

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Collection\CollectionTrait;
use AntoineDly\ORM\Dtos\DtoCollectionInterface;

/**
 * @implements DtoCollectionInterface<BindValueDto>
 */
final class BindValueDtoCollection implements DtoCollectionInterface
{
    /** @use CollectionTrait<BindValueDto> */
    use CollectionTrait;
}