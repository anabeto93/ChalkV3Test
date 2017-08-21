<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Normalizer;

use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Repository\User\ProgressionRepositoryInterface;

class CourseNormalizer
{
    const DEFAULT_FOLDER = 'default';

    /** @var FolderNormalizer */
    private $folderNormalizer;

    /** @var SessionNormalizer */
    private $sessionNormalizer;

    /** @var ProgressionRepositoryInterface */
    private $progressionRepository;

    /**
     * @param FolderNormalizer               $folderNormalizer
     * @param SessionNormalizer              $sessionNormalizer
     * @param ProgressionRepositoryInterface $progressionRepository
     */
    public function __construct(
        FolderNormalizer $folderNormalizer,
        SessionNormalizer $sessionNormalizer,
        ProgressionRepositoryInterface $progressionRepository
    ) {
        $this->folderNormalizer = $folderNormalizer;
        $this->sessionNormalizer = $sessionNormalizer;
        $this->progressionRepository = $progressionRepository;
    }

    /**
     * @param Course $course
     * @param User   $user
     *
     * @return array
     */
    public function normalize(Course $course, User $user): array
    {
        $sessions = $course->getSessions();

        $sessionsByFolder = [];
        $foldersNormalized = [];

        $progressions = $this->progressionRepository->findForUserAndCourse($user, $course);

        $progressions = $this->indexBySessionId($progressions);

        /** @var Session $session */
        foreach ($sessions as $session) {
            if ($session->hasFolder()) {
                $folder = $session->getFolder();

                if (!isset($foldersNormalized[$folder->getId()])) {
                    $foldersNormalized[$folder->getId()] = $this->folderNormalizer->normalize($folder);
                }

                $sessionsByFolder[$folder->getId()][] = $this->sessionNormalizer->normalize(
                    $session,
                    isset($progressions[$session->getId()])
                );
            } else {
                if (!isset($foldersNormalized[self::DEFAULT_FOLDER])) {
                    $foldersNormalized[self::DEFAULT_FOLDER] = $this->folderNormalizer->normalizeDefaultFolder();
                }

                $sessionsByFolder[self::DEFAULT_FOLDER][] = $this->sessionNormalizer->normalize(
                    $session,
                    isset($progressions[$session->getId()])
                );
            }
        }

        foreach ($foldersNormalized as $key => &$folder) {
            $folder['sessions'] = $sessionsByFolder[$key];
        }

        return [
            'uuid' => $course->getUuid(),
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'teacherName' => $course->getTeacherName(),
            'university' => $course->getUniversity(),
            'createdAt' => $course->getCreatedAt(),
            'folders' => $foldersNormalized,
        ];
    }

    /**
     * @param User\Progression[] $progressions
     *
     * @return User\Progression[]
     */
    private function indexBySessionId(array &$progressions): array
    {
        $progressionIndexed = [];

        foreach ($progressions as $progression) {
            $progressionIndexed[$progression->getSession()->getId()] = $progression;
        }

        return $progressionIndexed;
    }
}
