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
use App\Domain\Model\User;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Infrastructure\GraphQL\Resolver\HasUpdatesResolver;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class HasUpdatesResolverTest extends TestCase
{
    /** @var  ObjectProphecy */
    private $hasUpdatesChecker;

    /** @var  ObjectProphecy */
    private $tokenStorage;

    public function setUp()
    {
        $this->hasUpdatesChecker = $this->prophesize(HasUpdatesChecker::class);
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);
    }

    public function testErrorInDate()
    {
        $this->setExpectedException(UserError::class);

        $hasUpdatesResolver = new HasUpdatesResolver(
            $this->hasUpdatesChecker->reveal(),
            $this->tokenStorage->reveal()
        );
        $hasUpdatesResolver->resolveHasUpdates(new Argument(['dateLastUpdate' => false]));
    }

    public function testWithNoDateAndNoCourse()
    {
        $token = $this->prophesize(TokenInterface::class);
        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token->reveal());
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);
        $user->getEnabledCourses()->shouldBeCalled()->willReturn([]);
        $this->hasUpdatesChecker->getUpdatesInfo([], null)->shouldBeCalled()->willReturn([]);

        $hasUpdatesResolver = new HasUpdatesResolver(
            $this->hasUpdatesChecker->reveal(),
            $this->tokenStorage->reveal()
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
        $token = $this->prophesize(TokenInterface::class);
        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token->reveal());
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);
        $user->getEnabledCourses()->shouldBeCalled()->willReturn([]);
        $this->hasUpdatesChecker->getUpdatesInfo([], $dateTime)->shouldBeCalled()->willReturn([]);

        $hasUpdatesResolver = new HasUpdatesResolver(
            $this->hasUpdatesChecker->reveal(),
            $this->tokenStorage->reveal()
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
        $token = $this->prophesize(TokenInterface::class);
        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $course2 = $this->prophesize(Course::class);
        $course1 = $this->prophesize(Course::class);

        $courseUpdate = new CourseUpdateView($dateTime, 123);
        $folderUpdate = new FolderUpdateView($dateTime, 432);
        $sessionUpdate = new SessionUpdateView($dateTime, 0, $dateTime, 322);
        $sessionUpdate2 = new SessionUpdateView($dateTime, 99, $dateTime, 0);

        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token->reveal());
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);
        $user->getEnabledCourses()->shouldBeCalled()->willReturn([$course1->reveal(), $course2->reveal()]);
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
            $this->hasUpdatesChecker->reveal(),
            $this->tokenStorage->reveal()
        );
        $result = $hasUpdatesResolver->resolveHasUpdates(new Argument(['dateLastUpdate' => $dateTime]));

        $expected = [
            'hasUpdates' => true,
            'size' => 976
        ];

        $this->assertEquals($expected, $result);
    }
}
