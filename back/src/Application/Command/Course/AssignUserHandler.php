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

class AssignUserHandler
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /**
     * @param CourseRepositoryInterface $courseRepository
     */
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
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
