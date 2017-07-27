<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Course;

class CourseView
{
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $teacherName;

    /** @var string */
    public $university;

    /** @var bool */
    public $enabled;

    /**
     * @param int    $id
     * @param string $title
     * @param string $teacherName
     * @param string $university
     * @param bool   $enabled
     */
    public function __construct(
        int $id,
        string $title,
        string $teacherName,
        string $university,
        bool $enabled
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->teacherName = $teacherName;
        $this->university = $university;
        $this->enabled = $enabled;
    }
}