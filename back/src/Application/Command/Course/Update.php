<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Course;

use App\Domain\Model\Course;

class Update extends AbstractCourseCommand
{
    /** @var Course */
    public $course;

    /**
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
        $this->title = $course->getTitle();
        $this->description = $course->getDescription();
        $this->enabled = $course->isEnabled();
        $this->teacherName = $course->getTeacherName();
        $this->university = $course->getUniversity();
    }
}
