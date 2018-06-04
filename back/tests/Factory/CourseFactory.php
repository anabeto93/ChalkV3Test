<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Factory;

use App\Domain\Model\Course;
use App\Domain\Model\Institution;

class CourseFactory
{
    /**
     * @param Institution    $institution
     * @param string         $uuid
     * @param string         $title
     * @param \DateTime|null $dateTime
     *
     * @return Course
     */
    public static function create(
        Institution $institution,
        string $uuid = 'course-uuid',
        string $title = 'course title',
        \DateTime $dateTime = null
    ): Course {
        return new Course(
            $uuid,
            $institution,
            $title,
            'teacher Name',
            true,
            $dateTime !== null ? $dateTime : new \DateTime()
        );
    }
}
