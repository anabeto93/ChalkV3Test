<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\User\Import;

use App\Application\Query\Query;
use App\Domain\Model\Upload\File;

class ImportQuery implements Query
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
