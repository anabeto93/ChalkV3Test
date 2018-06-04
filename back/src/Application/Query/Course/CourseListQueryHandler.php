<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Course;

use App\Application\View\Course\CourseView;
use App\Domain\Repository\CourseRepositoryInterface;

class CourseListQueryHandler
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
     * @param CourseListQuery $courseListQuery
     *
     * @return array
     */
    public function handle(CourseListQuery $courseListQuery): array
    {
        $courseViews = [];
        $courses = $this->courseRepository->findByInstitution($courseListQuery->institution);

        foreach ($courses as $course) {
            $courseViews[] = new CourseView(
                $course->getId(),
                $course->getTitle(),
                $course->getTeacherName(),
                $course->isEnabled()
            );
        }

        return $courseViews;
    }
}
