<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Folder;

use App\Application\Command\Command;

abstract class AbstractFolderCommand implements Command
{
    /** @var int */
    public $rank;

    /** @var string */
    public $title;
}
