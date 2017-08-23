<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Course;

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

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param Calculator                $sizeCalculator
     * @param \DateTimeInterface        $dateTime
     */
    public function __construct(
        CourseRepositoryInterface $courseRepository,
        Calculator $sizeCalculator,
        \DateTimeInterface $dateTime
    ) {
        $this->courseRepository = $courseRepository;
        $this->sizeCalculator = $sizeCalculator;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Update $command
     */
    public function handle(Update $command)
    {
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
