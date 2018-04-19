<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Folder;

class FolderView
{
    /** @var int */
    public $id;

    /** @var int */
    public $rank;

    /** @var string */
    public $title;

    /**
     * @param int    $id
     * @param int    $rank
     * @param string $title
     */
    public function __construct(int $id, int $rank, string $title)
    {
        $this->id = $id;
        $this->rank = $rank;
        $this->title = $title;
    }
}
