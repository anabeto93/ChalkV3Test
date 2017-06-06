<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use Overblog\GraphQLBundle\Definition\Argument;

class CourseResolver
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
     * @param Argument $arguments
     *
     * @return array|null
     */
    public function resolveCourse(Argument $arguments)
    {
        $course = null;

        if (isset($arguments['uuid'])) {
            $course = $this->courseRepository->getByUuid($arguments['uuid']);

        }

        return $this->exportCourse($course);
    }

    /**
     * @param Argument $argument
     *
     * @return array
     */
    public function resolveCourses(Argument $argument)
    {
        $courses = [];
        $offset = isset($argument['offset']) ? $argument['offset'] : 1;

        if (isset($argument['limit'])) {
            $courseObjects = $this->courseRepository->paginate($offset, $argument['limit']);

            foreach ($courseObjects as $course) {
                $courses[] = $this->exportCourse($course);
            }
        }

        return $courses;
    }

    /**
     * @param Course|null $course
     *
     * @return array|null
     */
    private function exportCourse(Course $course = null)
    {
        if ($course === null) {
            return null;
        }

        return [
            'uuid' => $course->getUuid(),
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'teacherName' => $course->getTeacherName(),
            'createdAt' => $course->getCreatedAt(),
        ];
    }
}
