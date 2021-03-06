<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Core\Domain\Query\LanguageTreeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Mapper\LanguageTreeMapper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 */
class LanguageTreeProvider implements LanguageTreeProviderInterface
{
    /**
     * @var LanguageTreeQueryInterface
     */
    private LanguageTreeQueryInterface $query;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var LanguageTreeMapper
     */
    private LanguageTreeMapper $mapper;

    /**
     * @param LanguageTreeQueryInterface $query
     * @param TokenStorageInterface      $tokenStorage
     * @param LanguageTreeMapper         $mapper
     */
    public function __construct(
        LanguageTreeQueryInterface $query,
        TokenStorageInterface $tokenStorage,
        LanguageTreeMapper $mapper
    ) {
        $this->query = $query;
        $this->tokenStorage = $tokenStorage;
        $this->mapper = $mapper;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getActiveLanguages(Language $language): array
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $privileges = $user->getLanguagePrivilegesCollection();
            $tree = $this->query->getTree();

            return $this->mapper->map($language, $tree, $privileges);
        }

        return [];
    }
}
