<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Quiz;

use App\Domain\Exception\Session\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\SessionNotFoundException;
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;

class AnswerSessionQuizHandler
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param SessionRepositoryInterface     $sessionRepository
     * @param \DateTimeInterface             $dateTime
     */
    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        \DateTimeInterface $dateTime
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param AnswerSessionQuiz $answerSessionQuiz
     *
     * @return bool
     * @throws SessionNotFoundException
     * @throws SessionNotAccessibleForThisUserException
     */
    public function handle(AnswerSessionQuiz $answerSessionQuiz): bool
    {
        $session = $this->sessionRepository->getByUuid($answerSessionQuiz->sessionUuid);

        if (!$session instanceof Session) {
            throw new SessionNotFoundException(
                sprintf('The session "%s" can not be found', $answerSessionQuiz->sessionUuid)
            );
        }

        if (!$answerSessionQuiz->user->hasCourse($session->getCourse())) {
            throw new SessionNotAccessibleForThisUserException(
                sprintf('The session "%s" not accessible for this user', $answerSessionQuiz->sessionUuid)
            );
        }

        return true;
    }
}
