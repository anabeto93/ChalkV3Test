<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Session;

use App\Application\View\Session\Progression\UserValidatedView;
use App\Application\View\Session\Progression\UserView;
use App\Application\View\Session\ProgressionListView;
use App\Domain\Model\User;
use App\Domain\Model\UserCourse;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Domain\Repository\User\SessionQuizResultRepositoryInterface;
use App\Domain\Repository\UserCourseRepositoryInterface;

class ProgressionListQueryHandler
{
    /** @var UserCourseRepositoryInterface */
    private $userCourseRepository;

    /** @var ProgressionRepositoryInterface */
    private $progressionRepository;

    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /** @var SessionQuizResultRepositoryInterface */
    private $sessionQuizResultRepository;

    /**
     * @param UserCourseRepositoryInterface        $userCourseRepository
     * @param ProgressionRepositoryInterface       $progressionRepository
     * @param QuestionRepositoryInterface          $questionRepository
     * @param SessionQuizResultRepositoryInterface $sessionQuizResultRepository
     */
    public function __construct(
        UserCourseRepositoryInterface $userCourseRepository,
        ProgressionRepositoryInterface $progressionRepository,
        QuestionRepositoryInterface $questionRepository,
        SessionQuizResultRepositoryInterface $sessionQuizResultRepository
    ) {
        $this->userCourseRepository = $userCourseRepository;
        $this->progressionRepository = $progressionRepository;
        $this->questionRepository = $questionRepository;
        $this->sessionQuizResultRepository = $sessionQuizResultRepository;
    }

    /**
     * @param ProgressionListQuery $query
     *
     * @return ProgressionListView
     */
    public function handle(ProgressionListQuery $query): ProgressionListView
    {
        $userCourses = $this->userCourseRepository->findByCourse($query->session->getCourse());

        $users = $this->indexByUserId($userCourses);

        $userWithProgression = $this->progressionRepository->findForSession($query->session);

        $sessionHasQuiz = $this->questionRepository->sessionHasQuiz($query->session);
        $sessionQuizResultsIndexedByUserId = [];

        if ($sessionHasQuiz) {
            $sessionQuizResultsIndexedByUserId = $this->sessionQuizResultRepository->findForSession($query->session);
        }

        $progressionListView = new ProgressionListView($sessionHasQuiz);

        foreach ($userWithProgression as $progression) {
            $user = $progression->getUser();

            $this->removeFromUserList($user, $users);

            $userSessionQuizResult = null;

            if ($sessionHasQuiz) {
                $userSessionQuizResult = isset($sessionQuizResultsIndexedByUserId[$user->getId()])
                    ? $sessionQuizResultsIndexedByUserId[$user->getId()]
                    : null;
            }

            $userHasSessionQuizResult = null !== $userSessionQuizResult;
            $userPercentageQuizResult = true === $userHasSessionQuizResult
                ? $sessionQuizResultsIndexedByUserId[$user->getId()]->getCorrectAnswersPercentage()
                : null;

            $userValidatedView = new UserValidatedView(
                $user->getLastName(),
                $user->getFirstName(),
                $user->getPhoneNumber(),
                $progression->getMedium(),
                $progression->getCreatedAt(),
                $userHasSessionQuizResult,
                $userPercentageQuizResult
            );

            $progressionListView->addUserValidated($userValidatedView);
        }

        foreach ($users as $user) {
            $userView = new UserView(
                $user->getLastName(),
                $user->getFirstName(),
                $user->getPhoneNumber()
            );

            $progressionListView->addUserNotValidated($userView);
        }

        $progressionListView->sortUserByLastName();

        return $progressionListView;
    }

    /**
     * @param UserCourse[] $userCourses
     *
     * @return User[]
     */
    private function indexByUserId(array $userCourses): array
    {
        $usersIndexed = [];

        foreach ($userCourses as $userCourse) {
            $usersIndexed[$userCourse->getUser()->getId()] = $userCourse->getUser();
        }

        return $usersIndexed;
    }

    /**
     * @param User   $user
     * @param User[] $users
     */
    private function removeFromUserList(User $user, array &$users)
    {
        unset($users[$user->getId()]);
    }
}
