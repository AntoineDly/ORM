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
 * @implements DtoCollectionInterface<WherePartDtoInterface>
 */
final class RecursiveWherePartDtoCollection implements DtoCollectionInterface, WherePartDtoInterface
{
    /** @use CollectionTrait<WherePartDtoInterface> */
    use CollectionTrait;
    private const PREFIX_WHERE_BINDED = ':where_';

    private int $count = 0;

    public function __construct(
        private int $parentCount,
        private ComparaisonEnum $comparaison = ComparaisonEnum::FIRST,
    ) {
    }

    public function firstWhere(string $table, string $field, string|int $value, OperatorEnum $operator = OperatorEnum::EQUAL, PdoParamEnum $type = PdoParamEnum::STRING)
    {
        return $this->addWhere($table, $field, $value, ComparaisonEnum::FIRST, $operator, $type);
    }

    public function andWhere(string $table, string $field, string|int $value, OperatorEnum $operator = OperatorEnum::EQUAL, PdoParamEnum $type = PdoParamEnum::STRING)
    {
        return $this->addWhere($table, $field, $value, ComparaisonEnum::AND, $operator, $type);
    }

    public function orWhere(string $table, string $field, string|int $value, OperatorEnum $operator = OperatorEnum::EQUAL, PdoParamEnum $type = PdoParamEnum::STRING)
    {
        return $this->addWhere($table, $field, $value, ComparaisonEnum::OR, $operator, $type);
    }

    public function addWherePart(WherePartDtoInterface $wherePart): self
    {
        $this->add($wherePart);
        return $this;
    }


    public function getSQl(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $sql = ' '.$this->comparaison->getSQL().' ( ';

        foreach ($this->elements() as $element) {
            $sql .= $element->getSQl();
        }

        return $sql.' ) ';
    }

    public function getBindValues(): BindValueDtoCollection
    {
        if ($this->isEmpty()) {
            return BindValueDtoCollection::createEmpty();
        }

        /** @var BindValueDto[] $bindValueDtos */
        $bindValueDtos = [];

        foreach ($this->elements as $element) {
            $bindValueDtos = [
                ...$bindValueDtos,
                ...$element->getBindValues()->values()
            ];
        }

        return BindValueDtoCollection::create($bindValueDtos);
    }

    private function increment(): void
    {
        $this->count++;
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param PdoParamEnum $type
     * @return BindValueDto
     */
    private function getBindValueDto(string $field, int|string $value, PdoParamEnum $type): BindValueDto
    {
        $bindParam = self::PREFIX_WHERE_BINDED . $this->parentCount . '_' . $this->count . '_' . $field;
        $this->increment();

        return new BindValueDto(
            $bindParam,
            $value,
            $type
        );
    }

    private function addWhere(string $table, string $field, string|int $value, ComparaisonEnum $comparaison, OperatorEnum $operator = OperatorEnum::EQUAL, PdoParamEnum $type = PdoParamEnum::STRING)
    {
        $bindedValue = $this->getBindValueDto($field, $value, $type);

        $whereDto = new WherePartDto(
            $table,
            $field,
            $operator,
            $bindedValue,
            $comparaison
        );

        return $this->addWherePart($whereDto);
    }
}
