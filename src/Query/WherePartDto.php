<?php

namespace AntoineDly\ORM\Query;

final readonly class WherePartDto implements WherePartDtoInterface
{
    public function __construct(
        public string $table,
        public string $field,
        public OperatorEnum $operator,
        public BindValueDto $bindedValue,
        public ComparaisonEnum $comparaison,
    )
    {
    }

    public function getSql(): string
    {
        return $this->comparaison->value.'('.$this->table.'.'.$this->field.' '.$this->operator->value.' '.$this->bindedValue->param.')';
    }

    public function getBindValues(): BindValueDtoCollection
    {
        return BindValueDtoCollection::create([
            $this->bindedValue
        ]);
    }
}