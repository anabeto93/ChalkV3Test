<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Course;

use App\Domain\Model\UserCourse;

class HasUpdatesChecker
{
    /**
     * @param UserCourse[]            $userCourses
     * @param \DateTimeInterface|null $dateTime
     *
     * @return AbstractUpdateView[]
     */
    public function getUpdatesInfo(array $userCourses, \DateTimeInterface $dateTime = null): array
    {
        $updates = [];

        foreach ($userCourses as $userCourse) {
            $course = $userCourse->getCourse();

            if (!$course->isEnabled()) {
                continue;
            }

            $isNewAssignedCourse = $dateTime < $userCourse->getAssignedAt();

            if ($isNewAssignedCourse || $dateTime < $course->getUpdatedAt()) {
                $updates[] = new CourseUpdateView($course->getUpdatedAt(), $course->getSize());
            }

            foreach ($course->getSessions() as $session) {
                if ($isNewAssignedCourse
                    || $dateTime < $session->getUpdatedAt()
                    || $dateTime < $session->getContentUpdatedAt()
                ) {
                    $updates[] = new SessionUpdateView(
                        $session->getUpdatedAt(),
                        $isNewAssignedCourse || $dateTime < $session->getUpdatedAt() ? $session->getSize() : 0,
                        $session->getContentUpdatedAt(),
                        $isNewAssignedCourse || $dateTime < $session->getContentUpdatedAt() ? $session->getContentSize() : 0
                    );
                }
            }

            foreach ($course->getFolders() as $folder) {
                if ($isNewAssignedCourse || $dateTime < $folder->getUpdatedAt()) {
                    $updates[] = new FolderUpdateView($folder->getUpdatedAt(), $folder->getSize());
                }
            }
        }

        return $updates;
    }
}
