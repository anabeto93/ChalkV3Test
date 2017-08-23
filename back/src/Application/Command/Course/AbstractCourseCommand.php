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

abstract class AbstractCourseCommand implements Command
{
    /** @var string */
    public $title;

    /** @var string */
    public $teacherName;

    /** @var string */
    public $university;

    /** @var bool */
    public $enabled = false;

    /** @var string */
    public $description;
}
