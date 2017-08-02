<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\Import;

class ContentParsedView
{
    /** @var string */
    public $content;

    /** @var array */
    public $imagesFound;

    /**
     * @param string $content
     * @param array  $imagesFound
     */
    public function __construct(string $content, array $imagesFound)
    {
        $this->content = $content;
        $this->imagesFound = $imagesFound;
    }
}
