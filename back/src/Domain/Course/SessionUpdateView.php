<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Course;

class SessionUpdateView extends AbstractUpdateView
{
    /** @var \DateTimeInterface */
    public $contentUpdateDate;

    /** @var int */
    public $contentSize;

    /**
     * @param \DateTimeInterface $updateDate
     * @param int                $size
     * @param \DateTimeInterface $contentUpdateDate
     * @param int                $contentSize
     */
    public function __construct(
        \DateTimeInterface $updateDate,
        int $size = 0,
        \DateTimeInterface $contentUpdateDate,
        int $contentSize = 0
    ) {
        parent::__construct($updateDate, $size);

        $this->contentUpdateDate = $contentUpdateDate;
        $this->contentSize = $contentSize;
    }
}
