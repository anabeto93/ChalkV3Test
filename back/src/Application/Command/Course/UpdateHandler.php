<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Course;

use App\Application\Command\User\ForceUpdate;
use App\Application\Command\User\ForceUpdateHandler;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Size\Calculator;

class UpdateHandler
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /** @var Calculator */
    private $sizeCalculator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var ForceUpdateHandler */
    private $forceUpdateHandler;

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param ForceUpdateHandler        $forceUpdateHandler
     * @param Calculator                $sizeCalculator
     * @param \DateTimeInterface        $dateTime
     */
    public function __construct(
        CourseRepositoryInterface $courseRepository,
        ForceUpdateHandler $forceUpdateHandler,
        Calculator $sizeCalculator,
        \DateTimeInterface $dateTime
    ) {
        $this->courseRepository   = $courseRepository;
        $this->sizeCalculator     = $sizeCalculator;
        $this->dateTime           = $dateTime;
        $this->forceUpdateHandler = $forceUpdateHandler;
    }

    /**
     * @param Update $command
     */
    public function handle(Update $command)
    {
        if ($command->course->isEnabled() !== $command->enabled) {
            $this->forceUpdateHandler->handle(
                new ForceUpdate($command->course->getUsers())
            );
        }

        $command->course->update(
            $command->title,
            $command->description,
            $command->teacherName,
            $command->university,
            $command->enabled,
            $this->sizeCalculator->calculateSize(
                sprintf(
                    '%s%s%s%s%s',
                    $command->course->getUuid(),
                    $command->title,
                    $command->teacherName,
                    $command->university,
                    $command->description
                )
            ),
            $this->dateTime
        );

        $this->courseRepository->set($command->course);
    }
}
