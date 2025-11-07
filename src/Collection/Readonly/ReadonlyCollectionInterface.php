<?php

declare(strict_types=1);

namespace AntoineDly\ORM\Collection\Readonly;

use Countable;
use JsonSerializable;

/**
 * @template-covariant TReadonlyCollectionElement
 */
interface ReadonlyCollectionInterface extends Countable, JsonSerializable
{
    /**
     * @param  array-key  $offset
     * @return TReadonlyCollectionElement
     */
    public function offsetGet(mixed $offset): mixed;

    /** @param array-key $offset */
    public function offsetExists(mixed $offset): bool;

    public function createEmpty(): static;
}
