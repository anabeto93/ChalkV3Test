<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model;

class UserCourse
{
    /** @var User */
    private $user;

    /** @var Course */
    private $course;

    /** @var \DateTimeInterface */
    private $assignedAt;

    /**
     * @param $user
     * @param $course
     * @param $assignedAt
     */
    public function __construct(User $user, Course $course, \DateTimeInterface $assignedAt)
    {
        $this->user       = $user;
        $this->course     = $course;
        $this->assignedAt = $assignedAt;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getAssignedAt(): \DateTimeInterface
    {
        return $this->assignedAt;
    }
}
