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

interface CourseRepositoryInterface
{
    /**
     * @param Course $course
     */
    public function add(Course $course);

    /**
     * @return Course[]
     */
    public function getAll(): array;

    /**
     * @param string $uuid
     *
     * @return Course|null
     */
    public function getByUuid(string $uuid): ?Course;
}
