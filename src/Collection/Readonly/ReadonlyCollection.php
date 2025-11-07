<?php

declare(strict_types=1);

namespace AntoineDly\ORM\Collection\Readonly;

use AntoineDly\ORM\Collection\CollectionInterface;

/**
 * @template-covariant TReadonlyCollectionElement
 *
 * @implements ReadonlyCollectionInterface<TReadonlyCollectionElement>
 */
final readonly class ReadonlyCollection implements ReadonlyCollectionInterface
{
    /**
     * @param  CollectionInterface<TReadonlyCollectionElement>  $collection
     */
    public function __construct(
        private CollectionInterface $collection
    ) {
    }

    /**
     * @param  array-key  $offset
     * @return TReadonlyCollectionElement|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->collection->offsetGet($offset);
    }

    /** @param array-key $offset */
    public function offsetExists(mixed $offset): bool
    {
        return $this->collection->offsetExists($offset);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function createEmpty(): static
    {
        $this->collection::createEmpty();

        return $this;
    }

    /** @return TReadonlyCollectionElement[] */
    public function jsonSerialize(): mixed
    {
        return (array) $this->collection->jsonSerialize();
    }
}
