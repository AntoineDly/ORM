<?php

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Exceptions\TableIsEmptyException;

trait FieldTrait
{
    /** @var string[] */
    private array $fields = [];

    private function getFieldsString(): string
    {
        if (count($this->fields) === 0) {
            $this->setFields(['*']);
        }

        return implode(', ', $this->fields);
    }

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /** @param string[] $fields */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function addField(string $field): self
    {
        $this->fields[] = $field;
        return $this;
    }


    private function getFieldsSQL(): string
    {
        return ' '.$this->getFieldsString().' ';
    }
}
