<?php

declare(strict_types=1);

namespace AntoineDly\ORM\Dtos;

use AntoineDly\ORM\Collection\CollectionInterface;

/**
 * @template TDtoCollectionElement of DtoInterface
 *
 * @extends CollectionInterface<TDtoCollectionElement>
 */
interface DtoCollectionInterface extends CollectionInterface, DtoInterface
{
}
