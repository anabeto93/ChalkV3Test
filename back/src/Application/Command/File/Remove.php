<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\File;

use App\Application\Command\Command;
use App\Domain\Model\Upload\File;

class Remove implements Command
{
    /** @var File */
    public $file;

    /** @var string */
    public $dir;

    /**
     * @param File   $file
     * @param string $dir
     */
    public function __construct(File $file, string $dir)
    {
        $this->file = $file;
        $this->dir = $dir;
    }
}
