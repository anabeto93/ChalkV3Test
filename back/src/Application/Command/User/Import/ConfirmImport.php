<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Import;

use App\Application\Command\Command;
use App\Domain\Model\Upload\File;

class ConfirmImport implements Command
{
    /** @var File */
    public $file;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }
}
