<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;

class CourseManager
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
     * @param string $uuid
     * @param string $title
     * @param string $teacherName
     *
     * @return Course
     */
    public function create($uuid, $title, $teacherName = 'teacher')
    {
        $course = new Course($uuid, $title, $teacherName, new \DateTime());
        $this->courseRepository->add($course);

        return $course;
    }
}
