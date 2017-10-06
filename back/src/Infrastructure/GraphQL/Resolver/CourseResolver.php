<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Application\Command\User\UnForceUpdate;
use App\Application\Command\User\UnForceUpdateHandler;
use App\Infrastructure\Normalizer\CourseNormalizer;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CourseResolver
{
    /** @var CourseNormalizer */
    private $normalizer;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var UnForceUpdateHandler */
    private $unForceUpdateHandler;

    /**
     * @param CourseNormalizer      $normalizer
     * @param TokenStorageInterface $tokenStorage
     * @param UnForceUpdateHandler  $unForceUpdateHandler
     */
    public function __construct(
        CourseNormalizer $normalizer,
        TokenStorageInterface $tokenStorage,
        UnForceUpdateHandler $unForceUpdateHandler
    ) {
        $this->normalizer           = $normalizer;
        $this->tokenStorage         = $tokenStorage;
        $this->unForceUpdateHandler = $unForceUpdateHandler;
    }

    /**
     * @return array
     */
    public function resolveCourses(): array
    {
        $apiUser = $this->tokenStorage->getToken()->getUser();
        $courses = [];

        if (!$apiUser instanceof ApiUserAdapter) {
            throw new UserError('apiUser must be instance of ApiUserAdapter');
        }

        $user = $apiUser->getUser();

        $courseObjects = $user->getEnabledCourses();

        foreach ($courseObjects as $course) {
            $courses[] = $this->normalizer->normalize($course, $user);
        }

        $this->unForceUpdateHandler->handle(new UnForceUpdate($user));

        return $courses;
    }
}
