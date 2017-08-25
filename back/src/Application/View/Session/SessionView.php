<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Session;

class SessionView
{
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var int */
    public $rank;

    /** @var string|null */
    public $folderTitle;

    /** @var bool */
    public $needValidation;

    /**
     * @param int         $id
     * @param string      $title
     * @param int         $rank
     * @param string|null $folderTitle
     * @param bool        $needValidation
     */
    public function __construct(int $id, string $title, int $rank, string $folderTitle = null, bool $needValidation)
    {
        $this->id = $id;
        $this->title = $title;
        $this->rank = $rank;
        $this->folderTitle = $folderTitle;
        $this->needValidation = $needValidation;
    }
}
