<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\User;

use App\Application\Command\User\Create;
use App\Application\Command\User\CreateHandler;
use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class CreateHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $userRepository;

    /** @var ObjectProphecy */
    private $generator;

    /** @var \DateTime */
    private $dateTime;

    public function setUp()
    {
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->generator = $this->prophesize(Generator::class);
        $this->dateTime = new \DateTime();
    }

    public function testPhoneNumberAlreadyUsed()
    {
        // Expected
        $this->setExpectedException(PhoneNumberAlreadyUsedException::class);

        // Context
        $user = $this->prophesize(User::class);
        $command = new Create();
        $command->phoneNumber = '+123123123';

        // Mock
        $this->userRepository
            ->findByPhoneNumber('+123123123')
            ->shouldBeCalled()
            ->willReturn($user->reveal());

        // Handler
        $handler = new CreateHandler($this->userRepository->reveal(), $this->generator->reveal(), $this->dateTime);
        $handler->handle($command);
    }

    public function testHandle()
    {
        // Expected
        $expected = new User(
            'uuid-1',
            'firstName',
            'lastName',
            '+123123123',
            'FR',
            $this->dateTime
        );

        // Context
        $command = new Create();
        $command->phoneNumber = '+123123123';
        $command->firstName = 'firstName';
        $command->lastName = 'lastName';
        $command->country = 'FR';

        // Mock
        $this->userRepository->findByPhoneNumber('+123123123')->shouldBeCalled()->willReturn(null);
        $this->userRepository->add($expected)->shouldBeCalled();
        $this->generator->generateUuid()->shouldBeCalled()->willReturn('uuid-1');

        // Handler
        $handler = new CreateHandler($this->userRepository->reveal(), $this->generator->reveal(), $this->dateTime);
        $handler->handle($command);
    }
}
