<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Provider;

/**
 */
class AttributeTypeProvider
{
    /**
     * @var string[]
     */
    private array $types;

    /**
     * @param string ...$types
     */
    public function __construct(string ...$types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function provide(): array
    {
        return $this->types;
    }
}
