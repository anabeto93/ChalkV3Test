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
use App\Infrastructure\Normalizer\CourseNormalizer;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;

class CourseResolver
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /** @var CourseNormalizer */
    private $normalizer;

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param CourseNormalizer          $normalizer
     */
    public function __construct(
        CourseRepositoryInterface $courseRepository,
        CourseNormalizer $normalizer
    ) {
        $this->courseRepository = $courseRepository;
        $this->normalizer = $normalizer;
    }

    /**
     * @param Argument $arguments
     *
     * @return array
     */
    public function resolveCourse(Argument $arguments): array
    {
        $course = $this->courseRepository->getByUuid($arguments['uuid']);

        if (!$course instanceof Course) {
            throw new UserError('Course not found');
        }

        return $this->normalizer->normalize($course);
    }

    /**
     * @param Argument $argument
     *
     * @return array
     */
    public function resolveCourses(Argument $argument)
    {
        $courses = [];

        $courseObjects = $this->courseRepository->paginate($argument['offset'], $argument['limit']);

        if (empty($courseObjects)) {
            throw new UserError('No course found');
        }

        foreach ($courseObjects as $course) {
            $courses[] = $this->normalizer->normalize($course);
        }

        return $courses;
    }
}
