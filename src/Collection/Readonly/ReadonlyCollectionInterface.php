<?php

declare(strict_types=1);

/*
 * This file is part of the AntoineDly/ORM package.
 *
 * (c) Antoine Delaunay <antoine.delaunay333@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
