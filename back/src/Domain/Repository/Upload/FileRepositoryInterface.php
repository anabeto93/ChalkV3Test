<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository\Upload;

use App\Domain\Model\Upload\File;

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
