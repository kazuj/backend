<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Command\Create\CreateVariableProductCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;

/**
 */
class CreateVariableProductCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface   $tokenStorage;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $provider;

    /**
     * @param ProductRepositoryInterface   $productRepository
     * @param AttributeRepositoryInterface $attributeRepository
     * @param TokenStorageInterface        $tokenStorage
     * @param WorkflowProvider             $provider
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        TokenStorageInterface $tokenStorage,
        WorkflowProvider $provider
    ) {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->tokenStorage = $tokenStorage;
        $this->provider = $provider;
    }

    /**
     * @param CreateVariableProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateVariableProductCommand $command)
    {
        $attributes = $command->getAttributes();

        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $value = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $attributes[CreatedBySystemAttribute::CODE] = $value;
        }
        $workflow = $this->provider->provide();
        $attributes[StatusSystemAttribute::CODE] = new StringValue($workflow->getDefaultStatus()->getValue());

        $product = new VariableProduct(
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $attributes,
        );

        foreach ($command->getBindings() as $attributeId) {
            $attribute = $this->attributeRepository->load($attributeId);
            Assert::isInstanceOf($attribute, SelectAttribute::class);
            $product->addBind($attribute);
        }

        $this->productRepository->save($product);
    }
}
