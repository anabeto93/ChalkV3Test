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
use App\Domain\Model\Session;

interface SessionRepositoryInterface
{
    /**
     * @param Session $session
     */
    public function add(Session $session);

    /**
     * @param Session $session
     */
    public function set(Session $session);

    /**
     * @param Course $course
     *
     * @return Session[]
     */
    public function findByCourse(Course $course): array;
}
