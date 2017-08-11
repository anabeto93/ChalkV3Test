<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Folder;

use App\Domain\Model\Folder;

class Update extends AbstractFolderCommand
{
    /** @var Folder */
    public $folder;

    /**
     * @param Folder $folder
     */
    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
        $this->title = $folder->getTitle();
    }
}
