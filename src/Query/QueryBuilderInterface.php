<?php

namespace AntoineDly\ORM\Query;

interface QueryBuilderInterface
{
    public function build(): PreparedSQLDto;
}