<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\Validator\Constraint;

/**
 */
interface AttributeValueConstraintStrategyInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return Constraint
     */
    public function get(AbstractAttribute $attribute): Constraint;

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function supports(AbstractAttribute $attribute): bool;
}
