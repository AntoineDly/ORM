<?php

declare(strict_types=1);

namespace AntoineDly\ORM\Collection;

use AntoineDly\ORM\Collection\Readonly\ReadonlyCollectionInterface;
use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * @template TCollectionElement
 *
 * @extends ArrayAccess<array-key, TCollectionElement>
 * @extends IteratorAggregate<TCollectionElement>
 */
interface CollectionInterface extends ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    public static function createEmpty(): static;

    /** @return ReadonlyCollectionInterface<TCollectionElement> */
    public function getReadonlyCollection(): ReadonlyCollectionInterface;
}
