<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Normalizer\CourseNormalizer;
use GraphQL\Error\UserError;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CourseResolver
{
    /** @var CourseNormalizer */
    private $normalizer;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @param CourseNormalizer      $normalizer
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(CourseNormalizer $normalizer, TokenStorageInterface $tokenStorage)
    {
        $this->normalizer = $normalizer;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public function resolveCourses(): array
    {
        $apiUser = $this->tokenStorage->getToken()->getUser();
        $courses = [];

        $courseObjects = $apiUser->getUser()->getEnabledCourses();

        if (empty($courseObjects)) {
            throw new UserError('No course found');
        }

        foreach ($courseObjects as $course) {
            $courses[] = $this->normalizer->normalize($course, $apiUser->getUser());
        }

        return $courses;
    }
}
