<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\User;

use App\Application\Command\User\ForceUpdate;
use App\Application\Command\User\ForceUpdateHandler;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ForceUpdateHandlerTest extends TestCase
{
    public function testHandle()
    {
        $userOne   = $this->prophesize(User::class);
        $userTwo   = $this->prophesize(User::class);
        $userThree = $this->prophesize(User::class);

        $userOne->forceUpdate()->shouldBeCalled();
        $userTwo->forceUpdate()->shouldBeCalled();
        $userThree->forceUpdate()->shouldBeCalled();

        $users = [$userOne->reveal(), $userTwo->reveal(), $userThree->reveal()];

        $userRepository = $this->prophesize(UserRepositoryInterface::class);

        $userRepository->set($userOne->reveal())->shouldBeCalled();
        $userRepository->set($userTwo->reveal())->shouldBeCalled();
        $userRepository->set($userThree->reveal())->shouldBeCalled();

        $handler = new ForceUpdateHandler($userRepository->reveal());
        $handler->handle(new ForceUpdate($users));
    }
}
