<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Course;

use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Size\Calculator;
use App\Domain\Uuid\Generator;

class CreateHandler
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /** @var Generator */
    private $generator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var Calculator */
    private $sizeCalculator;

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param Generator                 $generator
     * @param Calculator                $sizeCalculator
     * @param \DateTimeInterface        $dateTime
     */
    public function __construct(
        CourseRepositoryInterface $courseRepository,
        Generator $generator,
        Calculator $sizeCalculator,
        \DateTimeInterface $dateTime
    ) {
        $this->courseRepository = $courseRepository;
        $this->generator = $generator;
        $this->dateTime = $dateTime;
        $this->sizeCalculator = $sizeCalculator;
    }

    /**
     * @param Create $command
     */
    public function handle(Create $command)
    {
        $uuid = $this->generator->generateUuid();
        $size = $this->sizeCalculator->calculateSize(
            sprintf(
                '%s%s%s%s',
                $uuid,
                $command->title,
                $command->teacherName,
                $command->description
            )
        );

        $course = new Course(
            $uuid,
            $command->institution,
            $command->title,
            $command->teacherName,
            $command->enabled,
            $this->dateTime,
            $command->description,
            $size
        );

        $this->courseRepository->add($course);
    }
}
