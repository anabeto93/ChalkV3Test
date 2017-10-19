<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository\User;

use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\User\Progression;

interface ProgressionRepositoryInterface
{
    /**
     * @param User    $user
     * @param Session $session
     *
     * @return Progression|null
     */
    public function findByUserAndSession(User $user, Session $session): ?Progression;

    /**
     * @param Progression $progression
     */
    public function add(Progression $progression);

    /**
     * @param User    $user
     * @param Course $course
     *
     * @return Progression[]
     */
    public function findForUserAndCourse(User $user, Course $course): array;

    /**
     * @param Session $session
     *
     * @return int
     */
    public function countUserForSession(Session $session): int;

    /**
     * @param Session $session
     *
     * @return Progression[]
     */
    public function findForSession(Session $session): array;
}
