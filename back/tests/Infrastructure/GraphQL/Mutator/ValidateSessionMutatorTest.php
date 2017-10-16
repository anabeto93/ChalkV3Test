<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\GraphQL\Mutator;

use App\Application\Adapter\CommandBusInterface;
use App\Application\Command\User\Progression\ValidateSession;
use App\Domain\Model\User;
use App\Domain\Exception\Session\ValidateSession\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\ValidateSession\SessionNotFoundException;
use App\Domain\User\Progression\Medium;
use App\Infrastructure\GraphQL\Mutator\ValidateSessionMutator;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ValidateSessionMutatorTest extends TestCase
{
    /** @var ObjectProphecy */
    private $commandBus;

    /** @var ObjectProphecy */
    private $tokenStorage;

    public function setUp()
    {
        $this->commandBus = $this->prophesize(CommandBusInterface::class);
        $this->tokenStorage = $this->prophesize(TokenStorageInterface::class);
    }

    public function testMutateNotFound()
    {
        $this->setExpectedException(UserError::class);
        $sessionUuid = 'not-found';

        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token);
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);

        $this->commandBus
            ->handle(new ValidateSession($user->reveal(), $sessionUuid, Medium::WEB))
            ->shouldBeCalled()
            ->willThrow(SessionNotFoundException::class)
        ;

        $mutator = new ValidateSessionMutator(
            $this->tokenStorage->reveal(),
            $this->commandBus->reveal()
        );
        $mutator->mutateValidateSession($sessionUuid);
    }

    public function testMutateNotAccessible()
    {
        $this->setExpectedException(UserError::class);
        $sessionUuid = '123-123';

        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token);
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);

        $this->commandBus
            ->handle(new ValidateSession($user->reveal(), $sessionUuid, Medium::WEB))
            ->shouldBeCalled()
            ->willThrow(SessionNotAccessibleForThisUserException::class)
        ;

        $mutator = new ValidateSessionMutator(
            $this->tokenStorage->reveal(),
            $this->commandBus->reveal()
        );
        $mutator->mutateValidateSession($sessionUuid);
    }

    public function testMutateHandle()
    {
        $sessionUuid = '123-123';

        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token);
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);

        $this->commandBus
            ->handle(new ValidateSession($user->reveal(), $sessionUuid, Medium::WEB))
            ->shouldBeCalled()
        ;

        $mutator = new ValidateSessionMutator(
            $this->tokenStorage->reveal(),
            $this->commandBus->reveal()
        );
        $result = $mutator->mutateValidateSession($sessionUuid);

        $this->assertTrue($result);
    }
}
