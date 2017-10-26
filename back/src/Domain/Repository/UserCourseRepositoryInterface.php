<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository;

use App\Domain\Model\Course;
use App\Domain\Model\UserCourse;

interface UserCourseRepositoryInterface
{
    /**
     * @param Course $course
     *
     * @return int
     */
    public function countUserForCourse(Course $course): int;

    /**
     * @param Course $course
     *
     * @return UserCourse[]
     */
    public function findByCourse(Course $course): array;
}
