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
        $user1 = $this->prophesize(User::class);
        $user2 = $this->prophesize(User::class);
        $paginatedResult = new PaginatedResult(
            [$user1->reveal(), $user2->reveal()],
            2,
            50,
            52
        );

        $user1->getId()->willReturn(1);
        $user2->getId()->willReturn(2);
        $user1->getFirstName()->willReturn('FirstName1');
        $user1->getLastName()->willReturn('LastName1');
        $user1->getPhoneNumber()->willReturn('+123123123');
        $user1->getCountry()->willReturn('FR');
        $user2->getFirstName()->willReturn('FirstName2');
        $user2->getLastName()->willReturn('LastName2');
        $user2->getPhoneNumber()->willReturn('+321321321');
        $user2->getCountry()->willReturn('GH');

        // Mock
        $userRepository = $this->prophesize(UserRepositoryInterface::class);
        $userRepository->paginate(2, 50)->shouldBeCalled()->willReturn($paginatedResult);

        // handler
        $handler = new UserListQueryHandler($userRepository->reveal());
        $result = $handler->handle(new UserListQuery(2));

        $expected = new UserListView(2, 2, 52);
        $expected->addUser(new UserView(1, 'FirstName1', 'LastName1', '+123123123', 'FR'));
        $expected->addUser(new UserView(2, 'FirstName2', 'LastName2', '+321321321', 'GH'));

        $this->assertEquals($expected, $result);
    }
}
