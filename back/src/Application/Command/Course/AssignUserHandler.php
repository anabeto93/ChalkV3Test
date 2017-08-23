<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Course;

use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class AssignUserHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /**
     * @param UserRepositoryInterface   $userRepository
     * @param CourseRepositoryInterface $courseRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, CourseRepositoryInterface $courseRepository)
    {
        $this->userRepository = $userRepository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @param AssignUser $assign
     */
    public function handle(AssignUser $assign)
    {
        $assign->course->affectUser($assign->users);
        $this->courseRepository->set($assign->course);
    }
}
