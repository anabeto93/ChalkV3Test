<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Folder;

use App\Application\Command\Command;

abstract class AbstractFolderCommand implements Command
{
    /** @var string */
    public $title;
}
