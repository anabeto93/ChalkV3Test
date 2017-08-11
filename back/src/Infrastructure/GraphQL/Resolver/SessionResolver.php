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
use App\Infrastructure\Normalizer\SessionNormalizer;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;

class SessionResolver
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var SessionNormalizer */
    private $sessionNormalizer;

    /**
     * @param SessionRepositoryInterface $sessionRepository
     * @param SessionNormalizer          $sessionNormalizer
     */
    public function __construct(SessionRepositoryInterface $sessionRepository, SessionNormalizer $sessionNormalizer)
    {
        $this->sessionRepository = $sessionRepository;
        $this->sessionNormalizer = $sessionNormalizer;
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
        $session = $this->sessionRepository->getByUuid($arguments['uuid']);

        if (!$session instanceof Session) {
            throw new UserError('Session not found');
        }

        return $this->sessionNormalizer->normalize($session);
    }
}
