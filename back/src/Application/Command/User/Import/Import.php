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
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Import implements Command
{
    /** @var UploadedFile */
    public $file;
}
