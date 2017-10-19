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
    public $hashedSrc;

    /** @var string */
    public $originalSrc;

    /**
     * @param string $tag
     * @param string $hashedSrc
     * @param string $originalSrc
     */
    public function __construct(
        string $tag,
        string $hashedSrc,
        string $originalSrc
    ) {
        $this->tag         = $tag;
        $this->hashedSrc   = $hashedSrc;
        $this->originalSrc = $originalSrc;
    }
}
