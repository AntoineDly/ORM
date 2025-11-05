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

namespace AntoineDly\ORM;

use AntoineDly\ORM\Entity\EntityInterface;
use AntoineDly\ORM\Exceptions\ConnexionPDOException;
use AntoineDly\ORM\Exceptions\EntityClassException;
use AntoineDly\ORM\Exceptions\ExecutionQueryException;
use AntoineDly\ORM\Exceptions\QueryTypeException;
use AntoineDly\ORM\Exceptions\SQLDirectionException;
use Exception;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionNamedType;

class ORM
{
    private const DATABASE = 'DATABASE';

    private string $host = '';
    private string $db = '';
    private string $user = '';
    private string $pass = '';
    private PDO $connexionPDO;
    /** @var array<array<string, string|int>> $where */
    private array $where = [];
    /** @var  array<int, array<string, string|null>> $fields */
    private array $fields;
    /** @var array<array<string, string|int>> $fieldsAndValues */
    private array $fieldsAndValues;
    /** @var array<array<string, string|null>> $order */
    private array $order;
    private ?int $limit = null;
    private ?int $offset = null;
    /** @var array<array<string, string|null>> $join */
    private array $join;

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function setDb(string $db): self
    {
        $this->db = $db;
        return $this;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;
        return $this;
    }

    public function connect(): void
    {
        try {
            $this->connexionPDO = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=utf8', $this->user, $this->pass);
            $this->connexionPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (Exception $e) {
            $this->logger->error(self::DATABASE, [$e->getMessage()]);
            throw new ConnexionPDOException($e->getMessage());
        }
    }

    public function get(EntityInterface $entity): EntityInterface
    {
        $query = $this->select($entity);
        $object = $query->fetch(PDO::FETCH_ASSOC);
        if (!is_array($object)) {
            $object = [];
        }
        return $this->populate($entity, $object);
    }

    /** @return array<int, EntityInterface>> */
    public function all(EntityInterface $entity): array
    {
        $objects = $this->select($entity)->fetchAll();
        return $this->populateAll($entity, $objects);
    }

    public function count(EntityInterface $entity): int
    {
        return $this->select($entity)->rowCount();
    }

    public function exist(EntityInterface $entity, int $value, string $field = 'id', string $type = 'int'): bool
    {
        $count = $this->where(field: $field, value: $value, type: $type)->limitBy(1)->count($entity);
        return $count === 1;
    }

    public function fields(string $field, ?string $table): self
    {
        $this->fields[] = [
            'table' => $table,
            'field' => $field
        ];

        return $this;
    }

    public function orderBy(string $field, ?string $table, string $direction): self
    {
        $directions = ['ASC', 'DESC'];
        if (!in_array($direction, $directions)) {
            $this->logger->error(
                self::DATABASE,
                ['Direction for ORDER is meant to be ASC or DESC : ' . $direction]
            );
            throw new SQLDirectionException('Direction for ORDER is meant to be ASC or DESC : ' . $direction);
        }

        $this->order[] = [
            'table' => $table,
            'field' => $field,
            'direction' => $direction
        ];
        return $this;
    }

    public function save(EntityInterface $class, EntityInterface $entity): bool
    {
        if (!$entity instanceof $class) {
            throw new EntityClassException($entity::class.' is different from '.$class::class);
        }
        $reflectionClass = new ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();
        $attributes = [];

        foreach ($properties as $property) {
            $attributes[] = [
                'field' => $property->getName(),
                'value' => $property->getValue($entity),
                'type' => ($property->getType() instanceof ReflectionNamedType) ? $property->getType()->getName() : null
            ];
        }
        $this->fieldsAndValues($attributes);

        if (isset($entity->id) && $this->exist($entity, $entity->id)) {
            $result = $this->where('id', (int)$entity->id, '=', 'int')->limitBy(1)->update($entity);
        } else {
            $result = $this->insert($entity);
        }
        return $result;
    }

    /**
     * @param string|array<int, string|null> $table
     * @param string|array<int, string> $key
     */
    public function join(array|string $table, string|array $key, string $type): self
    {
        if (is_array($table)) {
            $tablesJoined['first'] = $table[0];
            $tablesJoined['second'] = $table[1];
        } else {
            $tablesJoined['first'] = null;
            $tablesJoined['second'] = $table;
        }

        if (is_array($key)) {
            $keyJoined['first'] = $key[0];
            $keyJoined['second'] = $key[1];
        } else {
            $keyJoined['first'] = $keyJoined['second'] = $key;
        }

        $join = [
            'type' => $type,
            'firstTable' => $tablesJoined['first'],
            'secondTable' => $tablesJoined['second'],
            'firstKey' => $keyJoined['first'],
            'secondKey' => $keyJoined['second']
        ];

        $this->join[] = $join;
        return $this;
    }

    /** @param array<int, array<string, mixed>> $elements */
    public function fieldsAndValues(array $elements): self
    {
        foreach ($elements as $element) {
            if (is_string($element['field']) &&
                (is_string($element['value']) || is_int($element['value'])) &&
                is_string($element['type'])
            ) {
                $this->fieldAndValue($element['field'], $element['value'], $element['type']);
            }
        }
        return $this;
    }

    public function fieldAndValue(string $field, string|int $value, string $type = 'string'): self
    {
        $typeControl = $this->controlType($type);

        $this->fieldsAndValues[] = [
            'field' => $field,
            'value' => $value,
            'type' => $typeControl
        ];
        return $this;
    }

    public function limitBy(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function where(string $field, string|int $value, string $operator = '=', string $type = 'string'): self
    {
        $typeControl = $this->controlType($type);
        $this->where[] = [
            'field' => $field,
            'value' => $value,
            'operator' => $operator,
            'type' => $typeControl
        ];
        return $this;
    }

    /** @param array<string|int, string|int> $object */
    private function populate(EntityInterface $entity, array $object): EntityInterface
    {
        $newEntity = clone $entity;
        foreach ($object as $field => $value) {
            if (is_numeric($field)) {
                continue;
            }
            $newEntity->$field = $value;
        }
        return $newEntity;
    }

    /**
     * @param array<array<string|int, string|int>> $objects
     * @return array<int, EntityInterface>>
     */
    private function populateAll(EntityInterface $entity, array $objects): array
    {
        $entities = [];
        foreach ($objects as $object) {
            $entities[] = $this->populate($entity, $object);
        }

        return $entities;
    }

    private function controlType(string $type = 'string'): int
    {
        $pdoMap = [
            'integer' => PDO::PARAM_INT,
            'int' => PDO::PARAM_INT,
            'float' => PDO::PARAM_STR,
            'boolean' => PDO::PARAM_BOOL,
            'bool' => PDO::PARAM_BOOL,
            'string' => PDO::PARAM_STR,
            'text' => PDO::PARAM_STR
        ];

        if (!isset($pdoMap[$type])) {
            $this->logger->error(self::DATABASE, ['This PDO type doesn\'t exist : ' . $type]);
            throw new SQLDirectionException('This PDO type doesn\'t exist : ' . $type);
        }
        return $pdoMap[$type];
    }

    private function prepareQuery(string $sql, string $queryEnum = QueryEnum::SELECT): PDOStatement
    {
        if (!in_array($queryEnum, QueryEnum::QUERY_TYPES)) {
            $this->logger->error(
                self::DATABASE,
                ['QueryType doesn\'t exist : ' . $queryEnum]
            );
            throw new QueryTypeException('QueryType doesn\'t exist : ' . $queryEnum);
        }

        $query = $this->connexionPDO->prepare($sql);
        if (!empty($this->where)) {
            foreach ($this->where as $where) {
                $query->bindValue(':_where' . $where['field'], $where['value'], (int)$where['type']);
            }
        }
        if (!empty($this->fieldsAndValues)) {
            foreach ($this->fieldsAndValues as $fav) {
                $query->bindValue(':_fav' . $fav['field'], $fav['value'], (int)$fav['type']);
            }
        }
        return $query;
    }

    private function executeQuery(PDOStatement $query): bool
    {
        return $query->execute();
    }

    private function writeLimitBy(): string
    {
        return ' LIMIT ' . $this->limit;
    }

    private function writeOffset(): string
    {
        return ' OFFSET ' . $this->offset;
    }

    private function writeFields(EntityInterface $entity): string
    {
        $fields = [];
        foreach ($this->fields as $field) {
            if (is_null($field['table'])) {
                $field['table'] = $entity->getTable();
            }

            $fields[] = $field['table'] . '.' . $field['field'];
        }
        return implode(', ', $fields);
    }

    private function writeOrderBy(EntityInterface $entity): string
    {
        $directions = [];
        foreach ($this->order as $order) {
            if (is_null($order['table'])) {
                $order['table'] = $entity->getTable();
            }

            $directions[] = $order['table'] . '.' . $order['field'] . ' ' . $order['direction'];
        }
        return ' ORDER BY ' . implode(', ', $directions);
    }

    private function writeJoin(EntityInterface $entity): string
    {
        $conditions = [];
        foreach ($this->join as $join) {
            $condition = '';
            if ($join['type'] !== '') {
                $condition .= $join['type'] . ' ';
            }

            if (is_null($join['firstTable'])) {
                $join['firstTable'] = $entity->getTable();
            }

            $condition .= 'JOIN ' . $join['secondTable'] . ' ON ' .
                $join['firstTable'] . '.' . $join['firstKey'] . ' = ' .
                $join['secondTable'] . '.' . $join['secondKey'];
            $conditions[] = $condition;
        }
        return ' ' . implode(' ', $conditions);
    }

    private function updateWriteFieldsAndValues(): string
    {
        $conditions = [];
        foreach ($this->fieldsAndValues as $fav) {
            $conditions[] = $fav['field'] . ' = ' . ':_fav' . $fav['field'];
        }

        return implode(', ', $conditions);
    }

    private function insertWriteFieldsAndValues(): string
    {
        $fields = [];
        $bindFields = [];
        foreach ($this->fieldsAndValues as $fav) {
            $fields[] = $fav['field'];
            $bindFields[] = ':_fav' . $fav['field'];
        }

        return ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $bindFields) . ')';
    }

    private function writeWhere(): string
    {
        $whereQuery = ' WHERE ';
        $conditions = [];
        foreach ($this->where as $where) {
            $conditions[] = $where['field'] . ' ' . $where['operator'] . ' :_where' . $where['field'];
        }

        $whereQuery .= implode(' AND ', $conditions);
        return $whereQuery;
    }

    private function preSelect(EntityInterface $entity): string
    {
        $sql = 'SELECT ';
        if (empty($this->fields)) {
            $sql .= '*';
        } else {
            $sql .= $this->writeFields($entity);
        }
        $sql .= ' FROM ' . $entity->getTable();

        if (!empty($this->join)) {
            $sql .= $this->writeJoin($entity);
        }
        if (!empty($this->where)) {
            $sql .= $this->writeWhere();
        }
        if (!empty($this->order)) {
            $sql .= $this->writeOrderBy($entity);
        }
        if (!is_null($this->limit)) {
            $sql .= $this->writeLimitBy();
        }
        if (!is_null($this->offset)) {
            $sql .= $this->writeOffset();
        }

        return $sql;
    }

    private function select(EntityInterface $entity): PDOStatement
    {
        $sql = $this->preSelect($entity);
        $query = $this->prepareQuery($sql);
        $success = $this->executeQuery($query);
        if (!$success) {
            $this->logger->error(
                self::DATABASE,
                ['Error execution Query : ' . implode(' | ', $query->errorInfo())]
            );
            throw new ExecutionQueryException(
                'Error execution Query : ' . implode(' | ', $query->errorInfo())
            );
        }
        $this->clearParams();
        return $query;
    }

    private function clearParams(): void
    {
        $this->fields = [];
        $this->fieldsAndValues = [];
        $this->where = [];
        $this->order = [];
        $this->join = [];
        $this->limit = null;
        $this->offset = null;
    }

    private function preDelete(EntityInterface $entity): string
    {
        $sql = 'DELETE FROM ' . $entity->getTable();
        if (!empty($this->where)) {
            $sql .= $this->writeWhere();
        }

        return $sql;
    }

    public function delete(EntityInterface $entity): bool
    {
        $sql = $this->preDelete($entity);
        $query = $this->prepareQuery($sql, QueryEnum::DELETE);
        $success = $this->executeQuery($query);
        $this->clearParams();
        return $success;
    }

    private function preUpdate(EntityInterface $entity): string
    {
        $sql = 'UPDATE ' . $entity->getTable() . ' SET ';
        if (!empty($this->fieldsAndValues)) {
            $sql .= $this->updateWriteFieldsAndValues();
        }
        if (!empty($this->where)) {
            $sql .= $this->writeWhere();
        }

        return $sql;
    }

    public function update(EntityInterface $entity): bool
    {
        $sql = $this->preUpdate($entity);
        $query = $this->prepareQuery($sql, QueryEnum::UPDATE);
        $success = $this->executeQuery($query);
        $this->clearParams();
        return $success;
    }

    private function preInsert(EntityInterface $entity): string
    {
        $sql = 'INSERT INTO ' . $entity->getTable();
        if (!empty($this->fieldsAndValues)) {
            $sql .= $this->insertWriteFieldsAndValues();
        }

        return $sql;
    }

    private function insert(EntityInterface $entity): bool
    {
        $sql = $this->preInsert($entity);
        $query = $this->prepareQuery($sql, QueryEnum::INSERT);
        $success = $this->executeQuery($query);
        $this->clearParams();
        return $success;
    }
}
