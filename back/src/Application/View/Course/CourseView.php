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

    /** @var bool */
    public $enabled;

    /** @var int */
    public $numberOfFolders;

    /** @var int */
    public $numberOfSessions;

    /** @var int */
    public $numberOfStudents;

    /**
     * @param int    $id
     * @param string $title
     * @param string $teacherName
     * @param bool   $enabled
     * @param int    $numberOfFolders
     * @param int    $numberOfSessions
     * @param int    $numberOfStudents
     */
    public function __construct(
        int $id,
        string $title,
        string $teacherName,
        bool $enabled,
        int $numberOfFolders,
        int $numberOfSessions,
        int $numberOfStudents
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->teacherName = $teacherName;
        $this->enabled = $enabled;
        $this->numberOfFolders = $numberOfFolders;
        $this->numberOfSessions = $numberOfSessions;
        $this->numberOfStudents = $numberOfStudents;
    }
}
