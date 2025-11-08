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
