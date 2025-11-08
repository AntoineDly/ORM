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

final readonly class WherePartDto implements WherePartDtoInterface
{
    public function __construct(
        public string $table,
        public string $field,
        public OperatorEnum $operator,
        public BindValueDto $bindedValue,
        public ComparaisonEnum $comparaison,
    ) {
    }

    public function getSQL(): string
    {
        return ' '.$this->comparaison->getSQL().' ( '.$this->table.'.'.$this->field.' '.$this->operator->getSQL().' '.$this->bindedValue->param.' ) ';
    }

    public function getBindValues(): BindValueDtoCollection
    {
        return BindValueDtoCollection::create([
            $this->bindedValue
        ]);
    }
}
