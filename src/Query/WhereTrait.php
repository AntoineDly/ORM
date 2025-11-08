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

namespace AntoineDly\ORM\Query;

trait WhereTrait
{
    private int $whereCount = 0;
    private RecursiveWherePartDtoCollection $recursiveWherePartDtoCollection;

    private function instantiateRecursiveWherePartDtoCollection(): void
    {
        $this->recursiveWherePartDtoCollection = $this->createFirstRecursiveWherePartDtoCollection();
    }

    public function createFirstRecursiveWherePartDtoCollection(): RecursiveWherePartDtoCollection
    {
        return $this->createRecursiveWherePartDtoCollection(ComparaisonEnum::FIRST);
    }

    public function createAndRecursiveWherePartDtoCollection(): RecursiveWherePartDtoCollection
    {
        return $this->createRecursiveWherePartDtoCollection(ComparaisonEnum::AND);
    }

    public function createOrRecursiveWherePartDtoCollection(): RecursiveWherePartDtoCollection
    {
        return $this->createRecursiveWherePartDtoCollection(ComparaisonEnum::OR);
    }

    public function createRecursiveWherePartDtoCollection(ComparaisonEnum $comparaison): RecursiveWherePartDtoCollection
    {
        $whereDtoCollection = new RecursiveWherePartDtoCollection($this->whereCount, $comparaison);
        $this->increment();
        return $whereDtoCollection;
    }

    private function increment(): void
    {
        $this->whereCount++;
    }

    private function getWhereSQL(): string
    {
        $sql = $this->recursiveWherePartDtoCollection->getSQl();
        if ($sql === '') {
            return '';
        }
        return ' WHERE '.$sql;
    }

    private function getWhereBindValues(): BindValueDtoCollection
    {
        return $this->recursiveWherePartDtoCollection->getBindValues();
    }
}
