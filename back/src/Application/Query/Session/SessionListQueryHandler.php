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
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Domain\Repository\UserCourseRepositoryInterface;

class SessionListQueryHandler
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var ProgressionRepositoryInterface */
    private $progressionRepository;

    /** @var UserCourseRepositoryInterface */
    private $userCourseRepository;

    /**
     * @param SessionRepositoryInterface     $sessionRepository
     * @param ProgressionRepositoryInterface $progressionRepository
     * @param UserCourseRepositoryInterface  $userCourseRepository
     */
    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        ProgressionRepositoryInterface $progressionRepository,
        UserCourseRepositoryInterface $userCourseRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->progressionRepository = $progressionRepository;
        $this->userCourseRepository = $userCourseRepository;
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
        $numberOfStudentAssignToCourse = $this->userCourseRepository->countUserForCourse($query->course);

        foreach ($sessions as $session) {
            $progression = null;

            if ($session->needValidation()) {
                $progression = $this->progressionRepository->countUserForSession($session);
            }

            $sessionViews[] = new SessionView(
                $session->getId(),
                $session->getTitle(),
                $session->getRank(),
                $session->hasFolder() ? $session->getFolder()->getTitle() : null,
                $session->needValidation(),
                $session->isEnable(),
                $progression,
                $numberOfStudentAssignToCourse
            );
        }

        return $sessionViews;
    }
}
