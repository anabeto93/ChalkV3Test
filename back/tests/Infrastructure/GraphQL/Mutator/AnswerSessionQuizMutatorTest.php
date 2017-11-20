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
use App\Application\Command\User\Quiz\AnswerSessionQuiz;
use App\Domain\Model\User;
use App\Domain\Exception\Session\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\SessionNotFoundException;
use App\Infrastructure\GraphQL\Mutator\AnswerSessionQuizMutator;
use App\Infrastructure\Security\Api\ApiUserAdapter;
use GraphQL\Error\UserError;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AnswerSessionQuizMutatorTest extends TestCase
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

        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token);
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);

        $this
            ->commandBus
            ->handle(new ValidateSession($user->reveal(), 'not-found', 'web'))
            ->shouldBeCalled()
            ->willThrow(SessionNotFoundException::class)
        ;

        $mutator = new AnswerSessionQuizMutator(
            $this->tokenStorage->reveal(),
            $this->commandBus->reveal()
        );
        $mutator->handle('not-found', '1;2,3');
    }

    public function testMutateNotAccessible()
    {
        $this->setExpectedException(UserError::class);

        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token);
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);

        $this
            ->commandBus
            ->handle(new ValidateSession($user->reveal(), '123-123', 'web'))
            ->shouldBeCalled()
        ;

        $this
            ->commandBus
            ->handle(new AnswerSessionQuiz($user->reveal(), '123-123', '1;2,3', 'web'))
            ->shouldBeCalled()
            ->willThrow(SessionNotAccessibleForThisUserException::class)
        ;

        $mutator = new AnswerSessionQuizMutator(
            $this->tokenStorage->reveal(),
            $this->commandBus->reveal()
        );
        $mutator->handle('123-123', '1;2,3');
    }

    public function testMutateHandle()
    {
        $user = $this->prophesize(User::class);
        $apiUser = new ApiUserAdapter($user->reveal());
        $token = $this->prophesize(TokenInterface::class);
        $this->tokenStorage->getToken()->shouldBeCalled()->willReturn($token);
        $token->getUser()->shouldBeCalled()->willReturn($apiUser);

        $this
            ->commandBus
            ->handle(new ValidateSession($user->reveal(), '123-123', 'web'))
            ->shouldBeCalled()
        ;

        $this
            ->commandBus
            ->handle(new AnswerSessionQuiz($user->reveal(), '123-123', '1;2,3', 'web'))
            ->shouldBeCalled()
            ->willReturn(true)
        ;

        $mutator = new AnswerSessionQuizMutator(
            $this->tokenStorage->reveal(),
            $this->commandBus->reveal()
        );
        $result = $mutator->handle('123-123', '1;2,3');

        $this->assertTrue($result);
    }
}
