<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Query\User;

use App\Application\Query\User\UserListQuery;
use App\Application\Query\User\UserListQueryHandler;
use App\Application\View\User\UserListView;
use App\Application\View\User\UserView;
use App\Domain\Model\User;
use App\Domain\Pagination\PaginatedResult;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UserListQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Context
        $createdAt1 = new \DateTime('2017-01-01');
        $createdAt2 = new \DateTime('2017-05-05');
        $lastLoginAccessNotificationAt = new \DateTime('2017-09-01');

        $user1 = $this->prophesize(User::class);
        $user2 = $this->prophesize(User::class);
        $paginatedResult = new PaginatedResult(
            [$user1->reveal(), $user2->reveal()],
            2,
            500,
            530
        );

        $user1->getId()->willReturn(1);
        $user2->getId()->willReturn(2);
        $user1->getFirstName()->willReturn('FirstName1');
        $user1->getLastName()->willReturn('LastName1');
        $user1->getPhoneNumber()->willReturn('+123123123');
        $user1->getCountry()->willReturn('FR');
        $user1->getApiToken()->willReturn('token');
        $user1->getCreatedAt()->willReturn($createdAt1);
        $user1->getLastLoginAccessNotificationAt()->willReturn(null);

        $user2->getFirstName()->willReturn('FirstName2');
        $user2->getLastName()->willReturn('LastName2');
        $user2->getPhoneNumber()->willReturn('+321321321');
        $user2->getCountry()->willReturn('GH');
        $user2->getApiToken()->willReturn('token2');
        $user2->getCreatedAt()->willReturn($createdAt2);
        $user2->getLastLoginAccessNotificationAt()->willReturn($lastLoginAccessNotificationAt);

        // Mock
        $userRepository = $this->prophesize(UserRepositoryInterface::class);
        $userRepository->paginate(2, 500)->shouldBeCalled()->willReturn($paginatedResult);

        // handler
        $handler = new UserListQueryHandler($userRepository->reveal());
        $result = $handler->handle(new UserListQuery(2));

        $expected = new UserListView(2, 2, 530);
        $expected->addUser(new UserView(1, 'FirstName1', 'LastName1', '+123123123', 'FR', 'token', $createdAt1));
        $expected->addUser(
            new UserView(
                2,
                'FirstName2',
                'LastName2',
                '+321321321',
                'GH',
                'token2',
                $createdAt2,
                $lastLoginAccessNotificationAt
            )
        );

        $this->assertEquals($expected, $result);
    }
}
