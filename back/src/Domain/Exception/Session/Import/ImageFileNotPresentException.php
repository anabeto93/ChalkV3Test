<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Exception\Session\Import;

class ImageFileNotPresentException extends ImportException
{
    const MESSAGE = 'Image not present in ZIP file';

    /** @var int */
    public $fileName;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        parent::__construct(self::MESSAGE);

        $this->fileName = $fileName;
    }
}
