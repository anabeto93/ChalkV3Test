<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\Import;

class ImageView
{
    /** @var string */
    public $tag;

    /** @var string */
    public $src;

    public function __construct(string $tag, string $src)
    {
        $this->tag = $tag;
        $this->src = $src;
    }
}
