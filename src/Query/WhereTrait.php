<?php

namespace AntoineDly\ORM\Query;

trait WhereTrait
{
    private int $whereCount = 0;
    private RecursiveWhereDtoCollection $whereDtoCollection;

    private function instantiateWhereParts(): void
    {
        $this->whereDtoCollection = $this->createFirstWhereDtoCollection();
    }

    public function createFirstWhereDtoCollection(): RecursiveWhereDtoCollection
    {
        return $this->createWhereDtoCollection(ComparaisonEnum::FIRST);
    }

    public function createAndWhereDtoCollection(): RecursiveWhereDtoCollection
    {
        return $this->createWhereDtoCollection(ComparaisonEnum::AND);
    }

    public function createOrWhereDtoCollection(): RecursiveWhereDtoCollection
    {
        return $this->createWhereDtoCollection(ComparaisonEnum::OR);
    }

    public function createWhereDtoCollection(ComparaisonEnum $comparaison): RecursiveWhereDtoCollection
    {
        $whereDtoCollection = new RecursiveWhereDtoCollection($this->whereCount, $comparaison);
        $this->increment();
        return $whereDtoCollection;
    }

    private function increment(): void
    {
        $this->whereCount++;
    }

    private function getWhereSQL(): string
    {
        $sql = $this->whereDtoCollection->getSQl();
        if ($sql === '') {
            return '';
        }
        return ' WHERE '.$sql;
    }

    private function getWhereBindValues(): BindValueDtoCollection
    {
        return $this->whereDtoCollection->getBindValues();
    }
}
