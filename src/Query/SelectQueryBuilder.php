<?php

namespace AntoineDly\ORM\Query;

class SelectQueryBuilder implements QueryBuilderInterface
{
    use WhereTrait;
    use TableTrait;
    use FieldTrait;

    public function __construct()
    {
        $this->instantiateWhereParts();
    }

    public function build(): PreparedSQLDto
    {
        $sql = 'SELECT '
            . $this->getFieldsSQL()
            . $this->getTableSQL()
            . $this->getWhereSQL();

        $bindValues = BindValueDtoCollection::create([
            ...$this->getWhereBindValues()->elements()
        ]);

        return new PreparedSQLDto(
            $sql,
            $bindValues
        );
    }
}
