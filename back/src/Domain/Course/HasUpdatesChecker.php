<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Course;

use App\Domain\Model\Course;

class HasUpdatesChecker
{
    /**
     * @param Course[]                $courses
     * @param \DateTimeInterface|null $dateTime
     *
     * @return AbstractUpdateView[]
     */
    public function getUpdatesInfo(array $courses, \DateTimeInterface $dateTime = null): array
    {
        $updates = [];

        foreach ($courses as $course) {
            if ($dateTime < $course->getUpdatedAt()) {
                $updates[] = new CourseUpdateView($course->getUpdatedAt(), $course->getSize());
            }

            foreach ($course->getSessions() as $session) {
                if ($dateTime < $session->getUpdatedAt() || $dateTime < $session->getContentUpdatedAt()) {
                    $updates[] = new SessionUpdateView(
                        $session->getUpdatedAt(),
                        $dateTime < $session->getUpdatedAt() ? $session->getSize() : 0,
                        $session->getContentUpdatedAt(),
                        $dateTime < $session->getContentUpdatedAt() ? $session->getContentSize() : 0
                    );
                }
            }

            foreach ($course->getFolders() as $folder) {
                if ($dateTime < $folder->getUpdatedAt()) {
                    $updates[] = new FolderUpdateView($folder->getUpdatedAt(), $folder->getSize());
                }
            }
        }

        return $updates;
    }
}
