<?php

namespace atdrupal\efq_wrapper;

use EntityFieldQuery;

class EntityHintEFQ
{
    /** @var EntityFieldQuery */
    private $query;

    public function __construct($type, $bundle, EntityFieldQuery $query = null)
    {
        $this->query = $query ? $query : new EntityFieldQuery();
        $this->query->entityCondition('entity_type', $type);
        $this->query->entityCondition('bundle', $bundle);
    }

    public function __call($name, $arguments)
    {
        return $this->call($name, $arguments);
    }

    /**
     * Examples:
     *
     *  - $efq->where__field__field_body__value('%foo%', 'LIKE');
     *  - $efq->where__property__title('My node title');
     *  - $efq->sort__property__created('DESC');
     *
     * @param string   $name Magic method name.
     * @param string[] $arguments
     * @throws EntityHintEFQException
     * @return self|null
     */
    public function call($name, $arguments)
    {
        if (2 <= substr_count($name, '__')) {
            @list($which, $what, $how, $fieldColumn) = explode('__', $name);

            switch ($which) {
                case 'where':
                    return $this->where($what, $how, $arguments, $fieldColumn);
                case 'sort':
                    return $this->sort($what, $how, $arguments, $fieldColumn);
                default:
                    throw new EntityHintEFQException('Invalid method.');
            }
        }
    }

    /**
     * @param string      $what One of entity, property, field.
     * @param string      $how
     * @param string[]    $arguments
     * @param string|null $fieldColumn
     * @throws EntityHintEFQException
     * @return self
     */
    protected function where($what, $how, array $arguments, $fieldColumn = null)
    {
        switch ($what) {
            case 'entity':
                $this->query->entityCondition($how, $arguments[0], isset($arguments[1]) ? $arguments[1] : null);
                return $this;

            case 'property':
                $column = $arguments[0];
                $value = isset($arguments[1]) ? $arguments[1] : null;
                $this->query->propertyCondition($how, $column, $value);
                return $this;

            case 'field':
                $value = isset($arguments[0]) ? $arguments[0] : null;
                $operator = isset($arguments[1]) ? $arguments[1] : null;
                $deltaGroup = isset($arguments[2]) ? $arguments[2] : null;
                $languageGroup = isset($arguments[3]) ? $arguments[3] : null;
                $this->query->fieldCondition($how, $fieldColumn, $value, $operator, $deltaGroup, $languageGroup);
                return $this;

            default:
                throw new EntityHintEFQException('Invalid ::where() calling');
        }
    }

    /**
     * @param string      $what
     * @param string      $how
     * @param array       $arguments
     * @param string|null $fieldColumn
     * @throws EntityHintEFQException
     * @return self
     */
    protected function sort($what, $how, array $arguments = [], $fieldColumn = null)
    {
        switch ($what) {
            case 'entity':
                $direction = isset($arguments[0]) ? $arguments[0] : 'ASC';
                $this->query->entityOrderBy($how, $direction);
                return $this;

            case 'property':
                $direction = isset($arguments[0]) ? $arguments[0] : 'ASC';
                $this->query->propertyOrderBy($how, $direction);
                return $this;

            case 'field':
                $direction = isset($arguments[0]) ? $arguments[0] : 'ASC';
                $this->query->fieldOrderBy($how, $fieldColumn, $direction);
                return $this;

            default:
                throw new EntityHintEFQException('Invalid ::sort() calling');
        }
    }

    public function execute()
    {
        return $this->query->execute();
    }
}
