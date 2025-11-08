<?php

namespace AntoineDly\ORM\Query;

interface QueryFactoryInterface
{
    public function createSelectQueryBuilder(): SelectQueryBuilder;
    public function createUpdateQueryBuilder(): UpdateQueryBuilder;
    public function createInsertQueryBuilder(): InsertQueryBuilder;
    public function createDeleteQueryBuilder(): DeleteQueryBuilder;
    public function createQueryBuilder(): QueryBuilderInterface;
}
