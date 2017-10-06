<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Course;

use App\Domain\Model\UserCourse;
use App\Domain\Repository\CourseRepositoryInterface;

class AssignUserHandler
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param \DateTimeInterface        $dateTime
     */
    public function __construct(CourseRepositoryInterface $courseRepository, \DateTimeInterface $dateTime)
    {
        $this->courseRepository = $courseRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param AssignUser $assign
     */
    public function handle(AssignUser $assign)
    {
        $previousUsersAssigned = $assign->course->getUsers();

        foreach ($assign->users as $userAssigned) {
            if (!in_array($userAssigned, $previousUsersAssigned, true)) {
                $assign->course->addUserCourse(new UserCourse($userAssigned, $assign->course, $this->dateTime));
                $userAssigned->forceUpdate();
            }
        }

        foreach ($previousUsersAssigned as $previousUserAssigned) {
            if (!in_array($previousUserAssigned, $assign->users, true)) {
                $assign->course->removeUserCourse($previousUserAssigned, $assign->course);
                $previousUserAssigned->forceUpdate();
            }
        }

        $this->courseRepository->set($assign->course);
    }
}
