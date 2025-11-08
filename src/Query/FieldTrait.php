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

trait FieldTrait
{
    /** @var string[] */
    private array $fields = [];

    private function getFieldsString(): string
    {
        if (count($this->getFields()) === 0) {
            $this->setFields(['*']);
        }

        return implode(', ', $this->getFields());
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
