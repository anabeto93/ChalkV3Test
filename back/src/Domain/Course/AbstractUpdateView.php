<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Course;

abstract class AbstractUpdateView
{
    /** @var \DateTimeInterface */
    public $updateDate;

    /** @var int */
    public $size;

    /**
     * @param \DateTimeInterface $updateDate
     * @param int                $size
     */
    public function __construct(\DateTimeInterface $updateDate, int $size = 0)
    {
        $this->updateDate = $updateDate;
        $this->size = $size;
    }
}
