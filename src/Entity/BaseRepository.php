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

abstract class BaseRepository
{
    public function __construct(private EntityInterface $entity, private ORM $orm)
    {
        $orm->setEntity($this->entity);
    }

    /** @return EntityInterface[] */
    public function findAll(): array
    {
        return $this->orm->all();
    }

    public function findById(int $id): EntityInterface
    {
        return $this->find('id', $id, '=', 'integer');
    }

    public function find(string $field, string|int $value, string $operator = '=', string $type = 'string'): EntityInterface
    {
        return $this->orm->where($field, $value, $operator, $type)->get();
    }

    public function exist(int $id): bool
    {
        return $this->orm->exist($id);
    }

    public function save(EntityInterface $entity): bool
    {
        return $this->orm->save($entity);
    }

    public function update(string $field, string|int $value, string $type = 'string'): bool
    {
        return $this->orm->fieldAndValue($field, $value, $type)->update();
    }

    public function remove(string $field, string|int $value, string $operator = '=', string $type = 'string'): bool
    {
        return $this->orm->where($field, $value, $operator, $type)->remove();
    }


}
