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

final class SelectQueryBuilder implements QueryBuilderInterface
{
    use WhereTrait, TableTrait, FieldTrait, JoinTrait;

    public function __construct()
    {
        $this->instantiateRecursiveWherePartDtoCollection();
        $this->instantiateInnerJoinPartDtoCollection();
    }

    public function field(?string $table = null, string $field): self
    {
        if (is_null($table)) {
            $table = $this->getTable();
        }
        $this->addField($table.'.'.$field);
        return $this;
    }


    public function build(): PreparedSQLDto
    {
        $sql = 'SELECT '
            . $this->getFieldsSQL()
            . $this->getTableSQL()
            . $this->getJoinSQL()
            . $this->getWhereSQL();

        $bindValues = BindValueDtoCollection::create([
            ...$this->getWhereBindValues()->elements()
        ]);

        return new PreparedSQLDto(
            $sql,
            $bindValues
        );
    }
}
