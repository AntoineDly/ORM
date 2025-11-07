<?php

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Collection\CollectionTrait;
use AntoineDly\ORM\Dtos\DtoCollectionInterface;

/**
 * @implements DtoCollectionInterface<WherePartDtoInterface>
 */
final class RecursiveWhereDtoCollection implements DtoCollectionInterface, WherePartDtoInterface
{
    public const PREFIX_WHERE_BINDED = ':where_';

    /** @use CollectionTrait<WherePartDtoInterface> */
    use CollectionTrait;

    public int $count = 0;

    public function __construct(
        public int $parentCount,
        public ComparaisonEnum $comparaison = ComparaisonEnum::FIRST,
    )
    {
    }

    public function firstWhere(string $table, string $field, string|int $value, OperatorEnum $operator = OperatorEnum::EQUAL, PdoParamEnum $type = PdoParamEnum::STRING)
    {
        return $this->addWhere($table, $field, $value, ComparaisonEnum::FIRST, $operator, $type);
    }

    public function addWhere(string $table, string $field, string|int $value, ComparaisonEnum $comparaison, OperatorEnum $operator = OperatorEnum::EQUAL, PdoParamEnum $type = PdoParamEnum::STRING)
    {
        $bindParam = self::PREFIX_WHERE_BINDED.$this->parentCount.'_'.$this->count.'_'.$field;
        $this->count++;
        $bindedValue = new BindValueDto(
            $bindParam, $value, $type
        );

        $whereDto = new WherePartDto(
            $table, $field, $operator, $bindedValue, $comparaison
        );

        $this->add($whereDto);

        return $this;
    }


    public function getSQl(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $sql = $this->comparaison->value.'(';

        foreach ($this->elements as $element) {
            $sql .= $element->getSQl();
        }

        return $sql.')';
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
}
