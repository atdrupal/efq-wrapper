<?php

namespace atdrupal\efq_wrapper\tests;

use atdrupal\efq_wrapper\EntityHintEFQ;
use PHPUnit_Framework_TestCase;
use EntityFieldQuery;

class EntiyHintEFQTest extends PHPUnit_Framework_TestCase
{
    public function testQuery()
    {
        $query = $this
            ->getMockBuilder(EntityHintEFQ::class)
            ->setConstructorArgs(['node', 'article', $this->getEFQ()])
            ->setMethods(['__call'])
            ->getMock();

        $query->expects($this->any())
              ->method('__call')
              ->willReturnCallback(function ($name, $arguments) use ($query) {
                  return $query->call($name, $arguments);
              });

        $query
            ->where__entity__entity_id(1, '>=')
            ->where__property__title('FOO', '!=')
            ->where__field__field_body__value('%bar%', 'LIKE')
            ->sort__entity__entity_id()
            ->sort__entity__entity_label('DESC')
            ->sort__property__title()
            ->sort__property__created('DESC')
            ->sort__field__field_body__delta('DESC')
            ->execute();
    }

    private function getEFQ()
    {
        $class = EntityFieldQuery::class;
        $methods = ['entityCondition', 'propertyCondition', 'fieldCondition'];
        $methods = array_merge($methods, [
            'entityOrderBy',
            'propertyOrderBy',
            'fieldOrderBy'
        ]);
        $methods = array_merge($methods, ['execute']);
        $efq = $this->getMock($class, $methods);

        // Expecting internal calls
        $efq->expects($this->exactly(3))
            ->method('entityCondition')
            ->withConsecutive(
                ['entity_type', 'node'],
                ['bundle', 'article'],
                ['entity_id', 1, '>=']
            );

        $efq->expects($this->once())
            ->method('propertyCondition')
            ->with('title', 'FOO', '!=');

        $efq->expects($this->once())
            ->method('fieldCondition')
            ->with('field_body', 'value', '%bar%', 'LIKE');

        $efq->expects($this->exactly(2))
            ->method('entityOrderBy')
            ->withConsecutive(
                ['entity_id', 'ASC'],
                ['entity_label', 'DESC']
            );

        $efq->expects($this->exactly(2))
            ->method('propertyOrderBy')
            ->withConsecutive(
                ['title', 'ASC'],
                ['created', 'DESC']
            );

        $efq->expects($this->once())
            ->method('fieldOrderBy')
            ->with('field_body', 'delta', 'DESC');

        $efq
            ->expects($this->once())
            ->method('execute');

        return $efq;
    }
}
