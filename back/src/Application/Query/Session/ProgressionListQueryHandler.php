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
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Domain\Repository\UserCourseRepositoryInterface;

class ProgressionListQueryHandler
{
    /** @var UserCourseRepositoryInterface */
    private $userCourseRepository;

    /** @var ProgressionRepositoryInterface */
    private $progressionRepository;

    /**
     * @param UserCourseRepositoryInterface  $userCourseRepository
     * @param ProgressionRepositoryInterface $progressionRepository
     */
    public function __construct(
        UserCourseRepositoryInterface $userCourseRepository,
        ProgressionRepositoryInterface $progressionRepository
    ) {
        $this->userCourseRepository = $userCourseRepository;
        $this->progressionRepository = $progressionRepository;
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

        $progressionListView = new ProgressionListView();

        foreach ($userWithProgression as $progression) {
            $user = $progression->getUser();

            $this->removeFromUserList($user, $users);

            $userValidatedView = new UserValidatedView(
                $user->getLastName(),
                $user->getFirstName(),
                $user->getPhoneNumber(),
                $progression->getCreatedAt()
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
