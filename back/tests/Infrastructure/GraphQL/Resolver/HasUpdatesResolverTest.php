<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\GraphQL\Resolver;

use App\Domain\Course\CourseUpdateView;
use App\Domain\Course\FolderUpdateView;
use App\Domain\Course\HasUpdatesChecker;
use App\Domain\Course\SessionUpdateView;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Infrastructure\GraphQL\Resolver\HasUpdatesResolver;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class HasUpdatesResolverTest extends TestCase
{
    /** @var  ObjectProphecy */
    private $courseRepository;

    /** @var  ObjectProphecy */
    private $hasUpdatesChecker;

    public function setUp()
    {
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $this->hasUpdatesChecker = $this->prophesize(HasUpdatesChecker::class);
    }

    public function testErrorInDate()
    {
        $this->setExpectedException(UserError::class);

        $hasUpdatesResolver = new HasUpdatesResolver(
            $this->courseRepository->reveal(),
            $this->hasUpdatesChecker->reveal()
        );
        $hasUpdatesResolver->resolveHasUpdates(new Argument(['dateLastUpdate' => false]));
    }

    public function testWithNoDateAndNoCourse()
    {
        $this->courseRepository->getEnabledCourses()->shouldBeCalled()->willReturn([]);
        $this->hasUpdatesChecker->getUpdatesInfo([], null)->shouldBeCalled()->willReturn([]);

        $hasUpdatesResolver = new HasUpdatesResolver(
            $this->courseRepository->reveal(),
            $this->hasUpdatesChecker->reveal()
        );
        $result = $hasUpdatesResolver->resolveHasUpdates(new Argument());

        $expected = [
            'hasUpdates' => false,
            'size' => 0
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithNoCourse()
    {
        $dateTime = new \DateTime();
        $this->courseRepository->getEnabledCourses()->shouldBeCalled()->willReturn([]);
        $this->hasUpdatesChecker->getUpdatesInfo([], $dateTime)->shouldBeCalled()->willReturn([]);

        $hasUpdatesResolver = new HasUpdatesResolver(
            $this->courseRepository->reveal(),
            $this->hasUpdatesChecker->reveal()
        );
        $result = $hasUpdatesResolver->resolveHasUpdates(new Argument(['dateLastUpdate' => $dateTime]));

        $expected = [
            'hasUpdates' => false,
            'size' => 0
        ];

        $this->assertEquals($expected, $result);
    }

    public function testWithCourse()
    {
        $dateTime = new \DateTime();
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $courseUpdate = new CourseUpdateView($dateTime, 123);
        $folderUpdate = new FolderUpdateView($dateTime, 432);
        $sessionUpdate = new SessionUpdateView($dateTime, 0, $dateTime, 322);
        $sessionUpdate2 = new SessionUpdateView($dateTime, 99, $dateTime, 0);

        $this->courseRepository
            ->getEnabledCourses()
            ->shouldBeCalled()
            ->willReturn([$course1->reveal(), $course2->reveal()]);
        $this->hasUpdatesChecker
            ->getUpdatesInfo([$course1->reveal(), $course2->reveal()], $dateTime)
            ->shouldBeCalled()
            ->willReturn([
                $courseUpdate,
                $sessionUpdate,
                $sessionUpdate2,
                $folderUpdate,
            ]);

        $hasUpdatesResolver = new HasUpdatesResolver(
            $this->courseRepository->reveal(),
            $this->hasUpdatesChecker->reveal()
        );
        $result = $hasUpdatesResolver->resolveHasUpdates(new Argument(['dateLastUpdate' => $dateTime]));

        $expected = [
            'hasUpdates' => true,
            'size' => 976
        ];

        $this->assertEquals($expected, $result);
    }
}
