<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Folder;

use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Size\Calculator;
use App\Domain\Uuid\Generator;

class CreateHandler
{
    /** @var FolderRepositoryInterface */
    private $folderRepository;

    /** @var Generator */
    private $uuidGenerator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var Calculator */
    private $calculator;

    /**
     * @param FolderRepositoryInterface $folderRepository
     * @param Generator                 $generator
     * @param \DateTimeInterface        $dateTime
     * @param Calculator                $calculator
     */
    public function __construct(
        FolderRepositoryInterface $folderRepository,
        Generator $generator,
        \DateTimeInterface $dateTime,
        Calculator $calculator
    ) {
        $this->folderRepository = $folderRepository;
        $this->uuidGenerator = $generator;
        $this->dateTime = $dateTime;
        $this->calculator = $calculator;
    }

    /**
     * @param Create $command
     */
    public function handle(Create $command)
    {
        $uuid = $this->uuidGenerator->generateUuid();

        $folder = new Folder(
            $uuid,
            $command->rank,
            $command->title,
            $command->course,
            $this->dateTime,
            $this->calculator->calculateSize(sprintf('%s%s', $command->title, $uuid))
        );

        $this->folderRepository->add($folder);
    }
}
