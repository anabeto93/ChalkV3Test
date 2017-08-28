<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Course;

use App\Application\Command\Course\AssignUser;
use App\Application\Command\Course\AssignUserHandler;
use App\Domain\Repository\CourseRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\Factory\CourseFactory;

class AssignUserHandlerTest extends TestCase
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    public function setUp()
    {
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);
    }

    public function testHandle()
    {
        $course = CourseFactory::create();

        $this->courseRepository->set($course)->shouldBeCalled();

        $handler = new AssignUserHandler($this->courseRepository->reveal());

        $handler->handle(new AssignUser($course));
    }
}
