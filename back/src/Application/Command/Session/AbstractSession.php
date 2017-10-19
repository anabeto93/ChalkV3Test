<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Session;

use App\Domain\Model\Folder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractSession
{
    /** @var string */
    public $title;

    /** @var Folder|null */
    public $folder;

    /** @var int */
    public $rank;

    /** @var UploadedFile */
    public $content;

    /** @var bool */
    public $needValidation;
}
