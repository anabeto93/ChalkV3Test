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

class CourseNormalizer
{
    /**
     * @param Course $course
     *
     * @return array
     */
    public function normalize(Course $course)
    {
        return [
            'uuid' => $course->getUuid(),
            'title' => $course->getTitle(),
            'description' => $course->getDescription(),
            'teacherName' => $course->getTeacherName(),
            'createdAt' => $course->getCreatedAt(),
        ];
    }
}
