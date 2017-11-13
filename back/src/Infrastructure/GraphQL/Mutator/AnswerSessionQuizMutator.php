<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Mutator;

use App\Application\Adapter\CommandBusInterface;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AnswerSessionQuizMutator
{
    /** @var CommandBusInterface */
    private $commandBus;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     * @param CommandBusInterface   $commandBus
     */
    public function __construct(TokenStorageInterface $tokenStorage, CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $uuid
     * @param string $answers
     *
     * @return bool
     */
    public function handle(string $uuid, string $answers): bool
    {
        dump($uuid, $answers);
        $apiUser = $this->tokenStorage->getToken()->getUser();

        if (!$apiUser instanceof ApiUserAdapter) {
            throw new UserError('apiUser must be instance of ApiUserAdapter');
        }

        return true;
    }
}
