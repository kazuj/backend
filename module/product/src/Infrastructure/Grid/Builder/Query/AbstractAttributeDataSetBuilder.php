<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;

/**
 */
abstract class AbstractAttributeDataSetBuilder implements AttributeDataSetQueryBuilderInterface
{
    /**
     * @var LanguageQueryInterface
     */
    protected LanguageQueryInterface $query;

    /**
     * @var ProductAttributeLanguageResolver
     */
    protected ProductAttributeLanguageResolver $resolver;

    /**
     * @param LanguageQueryInterface           $query
     * @param ProductAttributeLanguageResolver $resolver
     */
    public function __construct(LanguageQueryInterface $query, ProductAttributeLanguageResolver $resolver)
    {
        $this->query = $query;
        $this->resolver = $resolver;
    }

    /**
     * @param QueryBuilder      $query
     * @param string            $key
     * @param AbstractAttribute $attribute
     * @param Language          $language
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $info = $this->query->getLanguageNodeInfo($this->resolver->resolve($attribute, $language));

        $query->addSelect(sprintf(
            '(
                SELECT value FROM product_value pv
                JOIN value_translation vt ON vt.value_id = pv.value_id
                LEFT JOIN language_tree lt ON lt.code = vt.language
                WHERE pv.attribute_id = \'%s\'
                AND pv.product_id = p.id
                AND lt.lft <= %s AND lt.rgt >= %s
                ORDER BY lft DESC NULLS LAST
                LIMIT 1           
            ) AS "%s"',
            $attribute->getId()->getValue(),
            $info['lft'],
            $info['rgt'],
            $key
        ));
    }
}
