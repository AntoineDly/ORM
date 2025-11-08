<?php

namespace AntoineDly\ORM\Query;

trait JoinTrait
{
    private JoinPartDtoCollection $joinPartDtoCollection;

    private function instantiateInnerJoinPartDtoCollection(): void
    {
        $this->joinPartDtoCollection = JoinPartDtoCollection::createEmpty();
    }

    public function getJoinSQL(): string
    {
        return $this->joinPartDtoCollection->getSQl();
    }
}