<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Session;

use App\Application\Query\Query;
use App\Domain\Model\Course;

class SessionListQuery implements Query
{
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
