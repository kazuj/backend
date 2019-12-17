<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Grid\Request\FilterValue;

/**
 */
abstract class AbstractDbalDataSet implements DataSetInterface
{
    /**
     * @param QueryBuilder          $query
     * @param FilterValueCollection $values
     * @param ColumnInterface[]     $columns
     */
    protected function buildFilters(QueryBuilder $query, FilterValueCollection $values, array $columns = []): void
    {
        /** @var FilterValue $filter */
        foreach ($values as $name => $filters) {
            if (array_key_exists($name, $columns)) {
                foreach ($filters as $filter) {
                    $columnFilter = $columns[$name]->getFilter();
                    if ($columnFilter) {
                        if ($columnFilter instanceof TextFilter) {
                            $this->buildTextQuery($query, $name, $filter->getOperator(), $filter->getValue());
                        } elseif ($columnFilter instanceof MultiSelectFilter) {
                            $this->buildMultiSelectQuery($query, $name, $filter->getOperator(), $filter->getValue());
                        } else {
                            $this->buildDefaultQuery($query, $name, $filter->getOperator(), $filter->getValue());
                        }
                    }
                }
            }
        }
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string       $operator
     * @param string|null  $value
     */
    private function buildTextQuery(QueryBuilder $query, string $field, string $operator, string $value = null): void
    {
        $query->andWhere($this->getExpresion($query, $field, $operator, $value));
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string       $operator
     * @param string|null  $givenValue
     */
    private function buildMultiSelectQuery(QueryBuilder $query, string $field, string $operator, string $givenValue = null): void
    {
        if (null !== $givenValue) {
            $values = explode(',', $givenValue);
            foreach ($values as $value) {
                $query->andWhere(sprintf(
                    'jsonb_exists_any("%s"::jsonb, %s::text[])',
                    $field,
                    $query->createNamedParameter(sprintf('{%s}', $value))
                ));
            }
        } else {
            $query->andWhere(sprintf('"%s"::TEXT = \'[]\'::TEXT', $field));
        }
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string       $operator
     * @param string|null  $value
     */
    private function buildDefaultQuery(QueryBuilder $query, string $field, string $operator, string $value = null): void
    {
        $query->andWhere($this->getExpresion($query, $field, $operator, $value));
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string       $operator
     * @param string       $value
     *
     * @return string
     */
    private function getExpresion(QueryBuilder $query, string $field, string $operator, ?string $value = null): string
    {
        $field = sprintf('"%s"', $field);

        if (null === $value) {
            return $query->expr()->isNull($field);
        }

        if ('>=' === $operator) {
            return $query->expr()->gte($field, $query->createNamedParameter($value));
        }

        if ('<=' === $operator) {
            return $query->expr()->lte($field, $query->createNamedParameter($value));
        }

        return \sprintf(
            '%s::TEXT ILIKE %s',
            $field,
            $query->createNamedParameter(\sprintf('%%%s%%', $this->escape($value)))
        );
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function escape(string $value): string
    {
        $replace = [
            '\\' => '\\\\',
            '%' => '\%',
            '_' => '\_',
        ];

        return str_replace(array_keys($replace), array_values($replace), $value);
    }
}