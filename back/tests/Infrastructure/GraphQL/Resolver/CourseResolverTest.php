<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\GraphQL\Resolver;

use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Infrastructure\GraphQL\Resolver\CourseResolver;
use App\Infrastructure\Normalizer\CourseNormalizer;
use GraphQL\Error\UserError;
use PHPUnit\Framework\TestCase;
use Overblog\GraphQLBundle\Definition\Argument;

class CourseResolverTest extends TestCase
{
    public function testResolveCourseNotFound()
    {
        $this->setExpectedException(UserError::class);

        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $normalizer = $this->prophesize(CourseNormalizer::class);

        $repository->getByUuid('1234-azerty')->shouldBeCalled()->willReturn(null);

        $courseResolver = new CourseResolver($repository->reveal(), $normalizer->reveal());
        $courseResolver->resolveCourse(new Argument(['uuid' => '1234-azerty']));
    }

    public function testResolveCourse()
    {
        $course = $this->prophesize(Course::class);
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $normalizer = $this->prophesize(CourseNormalizer::class);

        $repository->getByUuid('1234-azerty')->shouldBeCalled()->willReturn($course->reveal());
        $normalizer->normalize($course->reveal())->shouldBeCalled()->willReturn(['normalized-course']);

        $courseResolver = new CourseResolver($repository->reveal(), $normalizer->reveal());

        $result = $courseResolver->resolveCourse(new Argument(['uuid' => '1234-azerty']));
        $expected = ['normalized-course'];

        $this->assertEquals($expected, $result);
    }

    public function testResolveCoursesNotFound()
    {
        $this->setExpectedException(UserError::class);

        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $normalizer = $this->prophesize(CourseNormalizer::class);

        $repository->getEnabledCourses()->shouldBeCalled()->willReturn([]);

        $courseResolver = new CourseResolver($repository->reveal(), $normalizer->reveal());
        $courseResolver->resolveCourses();
    }

    public function testResolveCourses()
    {
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $repository = $this->prophesize(CourseRepositoryInterface::class);
        $normalizer = $this->prophesize(CourseNormalizer::class);

        $repository->getEnabledCourses()->shouldBeCalled()->willReturn([$course1->reveal(), $course2->reveal()]);
        $normalizer->normalize($course1->reveal())->shouldBeCalled()->willReturn(['normalized-course1']);
        $normalizer->normalize($course2->reveal())->shouldBeCalled()->willReturn(['normalized-course2']);

        $courseResolver = new CourseResolver($repository->reveal(), $normalizer->reveal());

        $result = $courseResolver->resolveCourses();
        $expected = [
            ['normalized-course1'],
            ['normalized-course2'],
        ];

        $this->assertEquals($expected, $result);
    }
}
