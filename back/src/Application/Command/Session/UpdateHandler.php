<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Session;

use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Session\Import\ContentImporter;
use App\Domain\Size\Calculator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateHandler
{
    /** @var ContentImporter */
    private $contentImporter;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var Calculator */
    private $calculator;

    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /**
     * @param SessionRepositoryInterface $sessionRepository
     * @param Calculator                 $calculator
     * @param ContentImporter            $contentImporter
     * @param \DateTimeInterface         $dateTime
     */
    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        Calculator $calculator,
        ContentImporter $contentImporter,
        \DateTimeInterface $dateTime
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->calculator        = $calculator;
        $this->contentImporter   = $contentImporter;
        $this->dateTime          = $dateTime;
    }

    /**
     * @param Update $command
     */
    public function handle(Update $command)
    {
        $command->session->update(
            $command->title,
            $command->rank,
            $command->folder,
            $command->needValidation,
            $command->enabled,
            $this->calculator->calculateSize(
                sprintf('%s%s%s', $command->session->getUuid(), $command->rank, $command->title)
            ),
            $this->dateTime
        );

        $this->sessionRepository->set($command->session);

        if ($command->content instanceof UploadedFile) {
            $this->contentImporter->importUpdateSession(
                $command->session,
                $command->content,
                $this->dateTime
            );
        }
    }
}
