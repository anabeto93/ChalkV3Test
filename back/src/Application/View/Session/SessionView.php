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

    /**
     * @param int         $id
     * @param string      $title
     * @param int         $rank
     * @param string|null $folderTitle
     */
    public function __construct(int $id, string $title, int $rank, string $folderTitle = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->rank = $rank;
        $this->folderTitle = $folderTitle;
    }
}
