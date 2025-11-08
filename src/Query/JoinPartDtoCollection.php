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

use AntoineDly\ORM\Collection\CollectionTrait;
use AntoineDly\ORM\Dtos\DtoCollectionInterface;

/**
 * @implements DtoCollectionInterface<JoinPartDto>
 */
final class JoinPartDtoCollection implements DtoCollectionInterface, JoinPartDtoInterface
{
    /** @use CollectionTrait<JoinPartDto> */
    use CollectionTrait;

    public function getSQl(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $sql = '';
        foreach ($this->elements() as $element) {
            $sql .= ' '.$element->getSQl().' ';
        }

        return $sql;
    }

    public function addJoin(string $table, string $field, string $linkedTable, string $linkedField): self
    {
        return $this->addJoinPart(JoinTypeEnum::JOIN, $table, $field, $linkedTable, $linkedField);
    }
    public function addLeftJoin(string $table, string $field, string $linkedTable, string $linkedField): self
    {
        return $this->addJoinPart(JoinTypeEnum::LEFT_JOIN, $table, $field, $linkedTable, $linkedField);
    }

    public function addRightJoin(string $table, string $field, string $linkedTable, string $linkedField): self
    {
        return $this->addJoinPart(JoinTypeEnum::RIGHT_JOIN, $table, $field, $linkedTable, $linkedField);
    }

    public function addInnerJoin(string $table, string $field, string $linkedTable, string $linkedField): self
    {
        return $this->addJoinPart(JoinTypeEnum::INNER_JOIN, $table, $field, $linkedTable, $linkedField);
    }

    public function addFullJoin(string $table, string $field, string $linkedTable, string $linkedField): self
    {
        return $this->addJoinPart(JoinTypeEnum::FULL_JOIN, $table, $field, $linkedTable, $linkedField);
    }

    public function addCrossJoin(string $table, string $field, string $linkedTable, string $linkedField): self
    {
        return $this->addJoinPart(JoinTypeEnum::CROSS_JOIN, $table, $field, $linkedTable, $linkedField);
    }

    private function addJoinPart(JoinTypeEnum $joinType, string $table, string $field, string $linkedTable, string $linkedField): self
    {
        return $this->add(new JoinPartDto(
           $joinType, $table, $field, $linkedTable, $linkedField
        ));
    }
}
