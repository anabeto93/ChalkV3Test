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
use App\Domain\Uuid\Generator;

class CreateHandler
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /** @var Generator */
    private $generator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param Generator                 $generator
     * @param \DateTimeInterface        $dateTime
     */
    public function __construct(
        CourseRepositoryInterface $courseRepository,
        Generator $generator,
        \DateTimeInterface $dateTime
    ) {
        $this->courseRepository = $courseRepository;
        $this->generator = $generator;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Create $command
     */
    public function handle(Create $command)
    {
        $size = mb_strlen(
            sprintf('%s%s%s%s', $command->title, $command->teacherName, $command->university, $command->description),
            '8bit'
        );

        $course = new Course(
            $this->generator->generateUuid(),
            $command->title,
            $command->teacherName,
            $command->university,
            $command->enabled,
            $this->dateTime,
            $command->description,
            $size
        );

        $this->courseRepository->add($course);
    }
}
