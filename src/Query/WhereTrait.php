<?php

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Exceptions\ComparaisonEnumException;

trait WhereTrait
{
    public int $whereCount = 0;
    public RecursiveWhereDtoCollection $whereDtoCollection;

    public function instantiateWhereParts()
    {
        $this->whereDtoCollection = new RecursiveWhereDtoCollection($this->whereCount);
    }
}