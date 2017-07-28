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
use App\Domain\Model\Folder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Create implements Command
{
    /** @var Course */
    public $course;

    /** @var string */
    public $title;

    /** @var Folder|null */
    public $folder;

    /** @var int */
    public $rank;

    /** @var UploadedFile */
    public $content;

    /**
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }
}
