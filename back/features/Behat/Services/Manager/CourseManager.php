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
use Tests\Factory\CourseFactory;

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
     *
     * @return Course
     */
    public function create($uuid, $title)
    {
        $course = CourseFactory::create($uuid, $title, new \DateTime());
        $this->courseRepository->add($course);

        return $course;
    }
}
