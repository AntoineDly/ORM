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

final readonly class JoinPartDto implements JoinPartDtoInterface
{
    public function __construct(
        public JoinTypeEnum $joinType,
        public string $table,
        public string $field,
        public string $linkedTable,
        public string $linkedField,
    )
    {
    }

    public function getSQl(): string
    {
        return $this->joinType->getSQL().' '.$this->table.' ON '.$this->linkedTable.'.'.$this->linkedField.' = '.$this->table.'.'.$this->field;
    }
}