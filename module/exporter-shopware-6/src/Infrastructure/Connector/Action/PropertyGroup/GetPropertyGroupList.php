<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\HeaderProviderInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetPropertyGroupList extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/v1/property-group?%s';

    /**
     * @var array|array[]
     */
    private array $query;

    /**
     * @param array    $query
     * @param int|null $limit
     * @param int|null $page
     */
    public function __construct(array $query = [], int $limit = 500, int $page = null)
    {
        $this->query = [
            'query' => $query ? $query : [],
        ];
        if ($limit > 0) {
            $this->query['limit'] = $limit;
        }
        if ($page > 0) {
            $this->query['page'] = $page;
        }
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_GET,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @param string|null $content
     *
     * @return array
     */
    public function parseContent(?string $content): array
    {
        $result = [];
        $data = json_decode($content, true);

        foreach ($data['data'] as $row) {
            $result[] = new Shopware6PropertyGroup(
                $row['id'],
                $row['attributes']['name'],
                $row['attributes']['displayType'],
                $row['attributes']['sortingType']
            );
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return rtrim(sprintf(self::URI, http_build_query($this->query)), '?');
    }
}
