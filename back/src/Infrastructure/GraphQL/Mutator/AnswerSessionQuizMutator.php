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
use App\Application\Command\User\Quiz\AnswerSessionQuiz;
use App\Domain\Exception\Session\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\SessionNotFoundException;
use App\Domain\Exception\Session\SessionQuizAnswerAlreadyExistsException;
use App\Domain\User\Progression\Medium;
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
        $apiUser = $this->tokenStorage->getToken()->getUser();

        if (!$apiUser instanceof ApiUserAdapter) {
            throw new UserError('apiUser must be instance of ApiUserAdapter');
        }

        try {
            return $this->commandBus->handle(new AnswerSessionQuiz($apiUser->getUser(), $uuid, $answers, Medium::WEB));
        } catch (SessionNotFoundException $sessionNotFoundException) {
            throw new UserError($sessionNotFoundException->getMessage());
        } catch (SessionNotAccessibleForThisUserException $sessionNotAccessibleForThisUserException) {
            throw new UserError($sessionNotAccessibleForThisUserException->getMessage());
        } catch (SessionQuizAnswerAlreadyExistsException $sessionQuizAnswerAlreadyExistsException) {
            throw new UserError($sessionQuizAnswerAlreadyExistsException->getMessage());
        }
    }
}
