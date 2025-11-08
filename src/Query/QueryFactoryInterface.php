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

interface QueryFactoryInterface
{
    public function createSelectQueryBuilder(): SelectQueryBuilder;
    public function createUpdateQueryBuilder(): UpdateQueryBuilder;
    public function createInsertQueryBuilder(): InsertQueryBuilder;
    public function createDeleteQueryBuilder(): DeleteQueryBuilder;
    public function createQueryBuilder(): QueryBuilderInterface;
}
