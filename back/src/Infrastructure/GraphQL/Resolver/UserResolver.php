<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Application\Command\User\TokenIssuedAt;
use App\Application\Command\User\TokenIssuedAtHandler;
use App\Infrastructure\Normalizer\UserNormalizer;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserResolver
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var UserNormalizer */
    private $userNormalizer;

    /** @var TokenIssuedAtHandler */
    private $tokenIssuedAtHandler;

    /**
     * UserResolver constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param UserNormalizer $userNormalizer
     * @param TokenIssuedAtHandler $tokenIssuedAtHandler
     */
    public function __construct(TokenStorageInterface $tokenStorage, UserNormalizer $userNormalizer, TokenIssuedAtHandler $tokenIssuedAtHandler) {
        $this->tokenStorage = $tokenStorage;
        $this->userNormalizer = $userNormalizer;
        $this->tokenIssuedAtHandler = $tokenIssuedAtHandler;
    }

    /**
     * @param Argument $args
     *
     * @return array
     *
     * @throw UserError
     */
    public function resolveUser(Argument $args): array
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof ApiUserAdapter) {
            throw new UserError('Invalid parameter');
        }

        if($args["tokenIssuedAt"]) {
            $tokenIssuedAt = new TokenIssuedAt($user->getUser());
            $tokenIssuedAt->apiTokenIssuedAt = date_create_from_format("Y-m-d H:i:s", $args["tokenIssuedAt"]);
            $this->tokenIssuedAtHandler->handle($tokenIssuedAt);
        }

        return $this->userNormalizer->normalize($user->getUser());
    }
}
