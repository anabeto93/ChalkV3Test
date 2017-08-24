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
use App\Domain\Model\User;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Infrastructure\GraphQL\Resolver\CourseResolver;
use App\Infrastructure\Normalizer\CourseNormalizer;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use PHPUnit\Framework\TestCase;
use Overblog\GraphQLBundle\Definition\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CourseResolverTest extends TestCase
{
    /** @var ObjectProphecy */
    private $normalizer;

    /** @var ObjectProphecy */
    private $tokenStorage;

    public function setUp()
    {
        $this->normalizer = $this->prophesize(CourseNormalizer::class);
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);
    }

    public function testResolveCoursesNotFound()
    {
        $this->setExpectedException(UserError::class);

        $token = $this->prophesize(TokenInterface::class);
        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token->reveal());
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);
        $user->getEnabledCourses()->shouldBeCalled()->willReturn([]);

        $courseResolver = new CourseResolver(
            $this->normalizer->reveal(),
            $this->tokenStorage->reveal()
        );
        $courseResolver->resolveCourses();
    }

    public function testResolveCourses()
    {
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);

        $token = $this->prophesize(TokenInterface::class);
        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token->reveal());
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);
        $user->getEnabledCourses()->shouldBeCalled()->willReturn([$course1->reveal(), $course2->reveal()]);

        $this->normalizer->normalize($course1->reveal())->shouldBeCalled()->willReturn(['normalized-course1']);
        $this->normalizer->normalize($course2->reveal())->shouldBeCalled()->willReturn(['normalized-course2']);

        $courseResolver = new CourseResolver(
            $this->normalizer->reveal(),
            $this->tokenStorage->reveal()
        );

        $result = $courseResolver->resolveCourses();
        $expected = [
            ['normalized-course1'],
            ['normalized-course2'],
        ];

        $this->assertEquals($expected, $result);
    }
}
