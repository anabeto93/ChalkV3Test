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

class CourseFactory
{
    /**
     * @param string         $uuid
     * @param string         $title
     * @param \DateTime|null $dateTime
     *
     * @return Course
     */
    public static function create(
        string $uuid = 'course-uuid',
        string $title = 'course title',
        \DateTime $dateTime = null
    ): Course {
        return new Course(
            $uuid,
            $title,
            'teacher Name',
            $dateTime !== null ? $dateTime : new \DateTime()
        );
    }
}
