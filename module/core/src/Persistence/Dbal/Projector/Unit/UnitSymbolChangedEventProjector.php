<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Projector\Unit;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Core\Domain\Event\UnitSymbolChangedEvent;

/**
 */
class UnitSymbolChangedEventProjector
{
    private const TABLE = 'unit';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param UnitSymbolChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UnitSymbolChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'symbol' => $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
