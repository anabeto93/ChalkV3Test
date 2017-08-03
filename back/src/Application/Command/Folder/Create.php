<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Folder;

use App\Application\Command\Command;
use App\Domain\Model\Course;

class Create implements Command
{
    /** @var Course */
    public $course;

    /** @var string */
    public $title;

    /**
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }
}
