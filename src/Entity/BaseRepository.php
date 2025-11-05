<?php

declare(strict_types=1);

/*
 * This file is part of the AntoineDly/Router package.
 *
 * (c) Antoine Delaunay <antoine.delaunay333@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AntoineDly\ORM\Entity;

use AntoineDly\ORM\ORM;

abstract readonly class BaseRepository
{
    public function __construct(private EntityInterface $entity, private ORM $orm)
    {
    }

    /** @return EntityInterface[] */
    public function findAll(): array
    {
        return $this->orm->all($this->entity);
    }

    public function findById(int $id): EntityInterface
    {
        return $this->find('id', $id, '=', 'integer');
    }

    public function find(string $field, string|int $value, string $operator = '=', string $type = 'string'): EntityInterface
    {
        return $this->orm->where($field, $value, $operator, $type)->get($this->entity);
    }

    public function exist(int $id): bool
    {
        return $this->orm->exist($this->entity, $id);
    }

    public function save(EntityInterface $entity): bool
    {
        return $this->orm->save($this->entity, $entity);
    }

    public function update(string $field, string|int $value, string $type = 'string'): bool
    {
        return $this->orm->fieldAndValue($field, $value, $type)->update($this->entity);
    }

    public function delete(string $field, string|int $value, string $operator = '=', string $type = 'string'): bool
    {
        return $this->orm->where($field, $value, $operator, $type)->delete($this->entity);
    }

}
