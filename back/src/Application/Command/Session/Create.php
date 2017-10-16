<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Session;

use App\Application\Command\Command;
use App\Domain\Model\Course;

class Create extends AbstractSession implements Command
{
    /** @var Course */
    public $course;

    /**
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
        $this->rank = 0;
    }
}
