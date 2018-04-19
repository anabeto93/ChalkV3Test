<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Folder;

use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Size\Calculator;

class UpdateHandler
{
    /** @var FolderRepositoryInterface */
    private $folderRepository;

    /** @var Calculator */
    private $calculator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param FolderRepositoryInterface $folderRepository
     * @param Calculator                $calculator
     * @param \DateTimeInterface        $dateTime
     */
    public function __construct(
        FolderRepositoryInterface $folderRepository,
        Calculator $calculator,
        \DateTimeInterface $dateTime
    ) {
        $this->folderRepository = $folderRepository;
        $this->calculator = $calculator;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Update $command
     */
    public function handle(Update $command)
    {
        $command->folder->update(
            $command->rank,
            $command->title,
            $this->calculator->calculateSize(
                sprintf('%s%s', $command->folder->getUuid(), $command->title)
            ),
            $this->dateTime
        );

        $this->folderRepository->set($command->folder);
    }
}
