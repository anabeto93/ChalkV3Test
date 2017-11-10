<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Infrastructure\Normalizer\SessionNormalizer;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionResolver
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var SessionNormalizer */
    private $sessionNormalizer;

    /** @var ProgressionRepositoryInterface */
    private $progressionRepository;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface          $tokenStorage
     * @param SessionRepositoryInterface     $sessionRepository
     * @param SessionNormalizer              $sessionNormalizer
     * @param ProgressionRepositoryInterface $progressionRepository
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionRepositoryInterface $sessionRepository,
        SessionNormalizer $sessionNormalizer,
        ProgressionRepositoryInterface $progressionRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->sessionNormalizer = $sessionNormalizer;
        $this->progressionRepository = $progressionRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Argument $arguments
     *
     * @return array
     *
     * @throws UserError
     */
    public function resolveSession(Argument $arguments): array
    {
        $apiUser = $this->tokenStorage->getToken()->getUser();
        $session = $this->sessionRepository->getByUuid($arguments['uuid']);

        if (!$session instanceof Session || !$session->isEnabled()) {
            throw new UserError('Session not found');
        }

        return $this->sessionNormalizer->normalize(
            $session,
            null !== $this->progressionRepository->findByUserAndSession($apiUser->getUser(), $session)
        );
    }
}
