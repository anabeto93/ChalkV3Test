<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\GraphQL\Resolver;

use App\Domain\Course\HasUpdatesChecker;
use App\Domain\Course\SessionUpdateView;
use App\Domain\Repository\CourseRepositoryInterface;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;

class HasUpdatesResolver
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /** @var HasUpdatesChecker */
    private $hasUpdatesChecker;

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param HasUpdatesChecker         $hasUpdatesChecker
     */
    public function __construct(CourseRepositoryInterface $courseRepository, HasUpdatesChecker $hasUpdatesChecker)
    {
        $this->courseRepository = $courseRepository;
        $this->hasUpdatesChecker = $hasUpdatesChecker;
    }

    /**
     * @param Argument $arguments
     *
     * @return array
     */
    public function resolveHasUpdates(Argument $arguments): array
    {
        $dateLastUpdate = isset($arguments['dateLastUpdate']) ? $arguments['dateLastUpdate'] : null;


        if (isset($arguments['dateLastUpdate']) && !$arguments['dateLastUpdate'] instanceof \DateTime) {
            throw new UserError('The date is not in the valid format ');
        }

        $info = $this->hasUpdatesChecker->getUpdatesInfo(
            $this->courseRepository->getAll(),
            $dateLastUpdate
        );

        $hasUpdates = !empty($info);
        $size = 0;

        foreach ($info as $updatedInfo) {
            $size += $updatedInfo->size;

            if ($updatedInfo instanceof SessionUpdateView) {
                $size += $updatedInfo->contentSize;
            }
        }

        return [
            'hasUpdates' => $hasUpdates,
            'size' => $size,
        ];
    }
}
