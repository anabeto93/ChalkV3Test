<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Session;

use App\Domain\Session\Import\ContentImporter;
use App\Domain\Uuid\Generator;

class CreateHandler
{
    /** @var Generator */
    private $generator;

    /** @var ContentImporter */
    private $contentImporter;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param Generator          $generator
     * @param ContentImporter    $contentImporter
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(Generator $generator, ContentImporter $contentImporter, \DateTimeInterface $dateTime)
    {
        $this->generator = $generator;
        $this->contentImporter = $contentImporter;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Create $create
     */
    public function handle(Create $create)
    {
        $sessionUuid = $this->generator->generateUuid();

        $this->contentImporter->importNewSession(
            $create->course,
            $sessionUuid,
            $create->rank,
            $create->title,
            $create->content,
            $this->dateTime,
            $create->folder,
            $create->needValidation
        );
    }
}
