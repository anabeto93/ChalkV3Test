<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Course;

use App\Application\Command\Command;
use App\Domain\Model\Course;
use App\Domain\Model\User;

class AssignUser implements Command
{
    /** @var User[] */
    public $users;

    /** @var Course */
    public $course;

    /**
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }
}
