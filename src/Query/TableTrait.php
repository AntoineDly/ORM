<?php

namespace AntoineDly\ORM\Query;

use AntoineDly\ORM\Exceptions\TableIsEmptyException;

trait TableTrait
{
    private string $table = '';

    private function getTable(): string
    {
        if ($this->table === '') {
            throw new TableIsEmptyException('Table is an empty string');
        }
        return $this->table;
    }

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    private function getTableSQL(): string
    {
        return ' FROM '.$this->getTable();
    }
}
