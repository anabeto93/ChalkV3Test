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

class CourseNormalizer
{
    const DEFAULT_FOLDER = 'default';

    /** @var FolderNormalizer */
    private $folderNormalizer;

    /** @var SessionNormalizer */
    private $sessionNormalizer;

    /**
     * @param FolderNormalizer  $folderNormalizer
     * @param SessionNormalizer $sessionNormalizer
     */
    public function __construct(FolderNormalizer $folderNormalizer, SessionNormalizer $sessionNormalizer)
    {
        $this->folderNormalizer = $folderNormalizer;
        $this->sessionNormalizer = $sessionNormalizer;
    }

    /**
     * @param Course $course
     *
     * @return array
     */
    public function normalize(Course $course): array
    {
        $sessions = $course->getSessions();

        $sessionsByFolder = [];
        $foldersNormalized = [];

        /** @var Session $session */
        foreach ($sessions as $session) {
            if ($session->hasFolder()) {
                $folder = $session->getFolder();
                if (!isset($foldersNormalized[$folder->getId()])) {
                    $foldersNormalized[$folder->getId()] = $this->folderNormalizer->normalize($folder);
                }

                $sessionsByFolder[$folder->getId()][] = $this->sessionNormalizer->normalize($session);
            } else {
                if (!isset($foldersNormalized[self::DEFAULT_FOLDER])) {
                    $foldersNormalized[self::DEFAULT_FOLDER] = $this->folderNormalizer->normalizeDefaultFolder();
                }

                $sessionsByFolder[self::DEFAULT_FOLDER][] = $this->sessionNormalizer->normalize($session);
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
            'createdAt' => $course->getCreatedAt(),
            'folders' => $foldersNormalized,
        ];
    }
}
