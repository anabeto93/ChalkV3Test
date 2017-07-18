<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\Repository\CourseRepositoryInterface;

class HasUpdatesResolver
{
    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @param CourseRepositoryInterface $courseRepository
     */
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @return array
     */
    public function resolveHasUpdates(): array
    {
        return [
            'hasUpdates' => true,
            'size' => 1234,56,
        ];
    }
}
