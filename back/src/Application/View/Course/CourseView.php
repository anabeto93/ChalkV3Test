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

    /** @var int */
    public $numberOfFolders;

    /** @var int */
    public $numberOfSessions;

    /** @var int */
    public $numberOfStudents;

    /** @var bool */
    public $enabled;

    /**
     * @param int    $id
     * @param string $title
     * @param string $teacherName
     * @param int    $numberOfFolders
     * @param int    $numberOfSessions
     * @param int    $numberOfStudents
     * @param bool   $enabled
     */
    public function __construct(
        int $id,
        string $title,
        string $teacherName,
        int $numberOfFolders,
        int $numberOfSessions,
        int $numberOfStudents,
        bool $enabled
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->teacherName = $teacherName;
        $this->numberOfFolders = $numberOfFolders;
        $this->numberOfSessions = $numberOfSessions;
        $this->numberOfStudents = $numberOfStudents;
        $this->enabled = $enabled;
    }
}
