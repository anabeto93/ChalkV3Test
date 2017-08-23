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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HasUpdatesResolver
{
    /** @var HasUpdatesChecker */
    private $hasUpdatesChecker;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @param HasUpdatesChecker     $hasUpdatesChecker
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(HasUpdatesChecker $hasUpdatesChecker, TokenStorageInterface $tokenStorage)
    {
        $this->hasUpdatesChecker = $hasUpdatesChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Argument $arguments
     *
     * @return array
     */
    public function resolveHasUpdates(Argument $arguments): array
    {
        $dateLastUpdate = $arguments['dateLastUpdate'] ?? null;

        if (isset($arguments['dateLastUpdate']) && !$arguments['dateLastUpdate'] instanceof \DateTime) {
            throw new UserError('The date is not in the valid format ');
        }

        $apiUser = $this->tokenStorage->getToken()->getUser();

        $info = $this->hasUpdatesChecker->getUpdatesInfo(
            $apiUser->getUser()->getEnabledCourses(),
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
