<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Session;

use App\Application\View\Session\SessionView;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Domain\Repository\User\SessionQuizResultRepositoryInterface;
use App\Domain\Repository\UserCourseRepositoryInterface;

class SessionListQueryHandler
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var ProgressionRepositoryInterface */
    private $progressionRepository;

    /** @var UserCourseRepositoryInterface */
    private $userCourseRepository;

    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /** @var SessionQuizResultRepositoryInterface */
    private $sessionQuizResultRepository;

    /**
     * @param SessionRepositoryInterface           $sessionRepository
     * @param ProgressionRepositoryInterface       $progressionRepository
     * @param UserCourseRepositoryInterface        $userCourseRepository
     * @param QuestionRepositoryInterface          $questionRepository
     * @param SessionQuizResultRepositoryInterface $sessionQuizResultRepository
     */
    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        ProgressionRepositoryInterface $progressionRepository,
        UserCourseRepositoryInterface $userCourseRepository,
        QuestionRepositoryInterface $questionRepository,
        SessionQuizResultRepositoryInterface $sessionQuizResultRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->progressionRepository = $progressionRepository;
        $this->userCourseRepository = $userCourseRepository;
        $this->questionRepository = $questionRepository;
        $this->sessionQuizResultRepository = $sessionQuizResultRepository;
    }

    /**
     * @param SessionListQuery $query
     *
     * @return SessionView[]
     */
    public function handle(SessionListQuery $query): array
    {
        $sessionViews = [];
        $sessions = $this->sessionRepository->findByCourse($query->course);
        $numberOfStudentAssignedToCourse = $this->userCourseRepository->countUserForCourse($query->course);

        foreach ($sessions as $session) {
            $usersValidated = null;

            if ($session->needValidation()) {
                $usersValidated = $this->progressionRepository->countUserForSession($session);
            }

            $hasQuiz = $this->questionRepository->sessionHasQuiz($session);

            $sessionViews[] = new SessionView(
                $session->getId(),
                $session->getTitle(),
                $session->getRank(),
                $session->hasFolder() ? $session->getFolder()->getTitle() : null,
                $session->needValidation(),
                $session->isEnabled(),
                $usersValidated,
                $numberOfStudentAssignedToCourse,
                $hasQuiz,
                $hasQuiz ? $this->sessionQuizResultRepository->countBySession($session) : null
            );
        }

        return $sessionViews;
    }
}
