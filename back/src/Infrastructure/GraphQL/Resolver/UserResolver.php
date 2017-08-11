<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Normalizer\UserNormalizer;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserResolver
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var UserNormalizer */
    private $userNormalizer;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param UserNormalizer        $userNormalizer
     */
    public function __construct(TokenStorageInterface $tokenStorage, UserNormalizer $userNormalizer)
    {
        $this->tokenStorage = $tokenStorage;
        $this->userNormalizer = $userNormalizer;
    }

    /**
     * @return array
     *
     * @throw UserError
     */
    public function resolveUser(): array
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof ApiUserAdapter) {
            throw new UserError('Invalid parameter');
        }

        return $this->userNormalizer->normalize($user->getUser());
    }
}
