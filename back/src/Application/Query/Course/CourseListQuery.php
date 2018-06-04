<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Course;

use App\Application\Query\Query;
use App\Domain\Model\Institution;

class CourseListQuery implements Query
{
    /** @var Institution */
    public $institution;

    /**
     * CourseListQuery constructor.
     * @param Institution $institution
     */
    public function __construct(Institution $institution)
    {
        $this->institution = $institution;
    }
}
