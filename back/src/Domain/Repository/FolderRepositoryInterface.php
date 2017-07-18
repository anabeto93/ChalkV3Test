<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository;

use App\Domain\Model\Folder;

interface FolderRepositoryInterface
{
    /**
     * @param Folder $folder
     */
    public function add(Folder $folder);
}
