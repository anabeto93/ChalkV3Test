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
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Repository\User\ProgressionRepositoryInterface;

class CourseNormalizer
{
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
        $sessions = $course->getEnabledSessions();

        $sessionsByFolder = [];
        $foldersNormalized = [];

        $progressions = $this->indexBySessionId($this->progressionRepository->findForUserAndCourse($user, $course));

        /** @var Session $session */
        foreach ($sessions as $session) {
            $folderId = Folder::DEFAULT_FOLDER;
            $folder = $session->getFolder();

            if (null !== $folder) {
                $folderId = $session->getFolder()->getId();
            }

            if (!isset($foldersNormalized[$folderId])) {
                $foldersNormalized[$folderId] = $this->folderNormalizer->normalize($folder);
            }

            $sessionsByFolder[$folderId][] = $this->sessionNormalizer->normalize(
                $session,
                isset($progressions[$session->getId()])
            );
        }

        foreach ($foldersNormalized as $key => &$folder) {
            $folder['sessions'] = $sessionsByFolder[$key];
        }

        $this->sortFoldersByRank($foldersNormalized);

        return [
            'uuid' => $course->getUuid(),
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'teacherName' => $course->getTeacherName(),
            'university' => $course->getInstitution()->getName(),
            'createdAt' => $course->getCreatedAt(),
            'updatedAt' => $course->getUpdatedAt(),
            'folders' => $foldersNormalized,
        ];
    }

    /**
     * @param User\Progression[] $progressions
     *
     * @return User\Progression[]
     */
    private function indexBySessionId(array $progressions): array
    {
        $progressionIndexed = [];

        foreach ($progressions as $progression) {
            $progressionIndexed[$progression->getSession()->getId()] = $progression;
        }

        return $progressionIndexed;
    }

    /**
     * @param array $folders
     */
    private function sortFoldersByRank(array &$folders) {
        if(count($folders) > 1) {
            usort(
                $folders,
                function ($one, $other) {
                    return $one["rank"] > $other["rank"];
                }
            );
        }
    }
}

