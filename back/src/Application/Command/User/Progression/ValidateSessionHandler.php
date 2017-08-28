<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Progression;

use App\Domain\Model\User\Progression;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Domain\Session\ValidateSession\SessionNotAccessibleForThisUserException;
use App\Domain\Session\ValidateSession\SessionNotFoundException;

class ValidateSessionHandler
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var ProgressionRepositoryInterface */
    private $progressionRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param SessionRepositoryInterface     $sessionRepository
     * @param ProgressionRepositoryInterface $progressionRepository
     * @param \DateTimeInterface             $dateTime
     */
    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        ProgressionRepositoryInterface $progressionRepository,
        \DateTimeInterface $dateTime
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->progressionRepository = $progressionRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param ValidateSession $command
     */
    public function handle(ValidateSession $command)
    {
        $session = $this->sessionRepository->getByUuid($command->sessionUuid);

        if (null === $session) {
            throw new SessionNotFoundException(
                sprintf('The session "%s" can not be found', $command->sessionUuid)
            );
        }

        if (!$command->user->hasCourse($session->getCourse())) {
            throw new SessionNotAccessibleForThisUserException(
                sprintf('The session "%s" not accessible for this user', $command->sessionUuid)
            );
        }

        $progression = $this->progressionRepository->findByUserAndSession($command->user, $session);

        if (null === $progression) {
            $progression = new Progression($command->user, $session, $this->dateTime);

            $this->progressionRepository->add($progression);
        }
    }
}
