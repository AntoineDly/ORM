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
