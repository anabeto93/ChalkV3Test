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
use App\Application\Command\User\Progression\ValidateSession;
use App\Domain\Exception\Session\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\SessionNotFoundException;
use App\Domain\User\Progression\Medium;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ValidateSessionMutator
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
     * @param string $sessionUuid
     *
     * @throws SessionNotFoundException
     * @throws SessionNotAccessibleForThisUserException
     *
     * @return bool
     */
    public function mutateValidateSession(string $sessionUuid): bool
    {
        try {
            /** @var ApiUserAdapter $apiUser */
            $apiUser = $this->tokenStorage->getToken()->getUser();

            $this->commandBus->handle(new ValidateSession($apiUser->getUser(), $sessionUuid, Medium::WEB));

            return true;
        } catch (SessionNotFoundException $exception) {
            throw new UserError($exception->getMessage());
        } catch (SessionNotAccessibleForThisUserException $exception) {
            throw new UserError($exception->getMessage());
        }
    }
}
