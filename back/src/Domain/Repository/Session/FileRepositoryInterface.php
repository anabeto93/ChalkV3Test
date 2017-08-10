<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository\Session;

use App\Domain\Model\Session\File;

interface FileRepositoryInterface
{
    /**
     * @param File $file
     */
    public function add(File $file);

    /**
     * @param File $file
     */
    public function remove(File $file);
}
