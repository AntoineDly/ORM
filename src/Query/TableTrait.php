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

use AntoineDly\ORM\Exceptions\TableIsEmptyException;

trait TableTrait
{
    private string $table = '';

    private function getTableString(): string
    {
        if ($this->getTable() === '') {
            throw new TableIsEmptyException('Table is an empty string');
        }
        return $this->getTable();
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    private function getTableSQL(): string
    {
        return ' FROM '.$this->getTableString();
    }
}
