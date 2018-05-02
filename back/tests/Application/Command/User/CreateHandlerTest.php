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
use App\Domain\Size\Calculator;
use App\Domain\Uuid\Generator;
use App\Infrastructure\Service\TokenGenerator;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class CreateHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sizeCalculator;

    /** @var ObjectProphecy */
    private $userRepository;

    /** @var ObjectProphecy */
    private $generator;

    /** @var ObjectProphecy */
    private $tokenGenerator;

    /** @var \DateTime */
    private $dateTime;

    /** @var ObjectProphecy */
    private $localeHelper;

    public function setUp()
    {
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->generator = $this->prophesize(Generator::class);
        $this->tokenGenerator = $this->prophesize(TokenGenerator::class);
        $this->sizeCalculator = $this->prophesize(Calculator::class);
        $this->localeHelper = $this->prophesize(LocaleHelper::class);
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
        $handler = new CreateHandler(
            $this->userRepository->reveal(),
            $this->generator->reveal(),
            $this->tokenGenerator->reveal(),
            $this->sizeCalculator->reveal(),
            $this->localeHelper->reveal(),
            $this->dateTime,
            false
        );
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
            'fr',
            39,
            '2oKenN',
            $this->dateTime,
            false
        );

        // Context
        $command = new Create();
        $command->phoneNumber = '+123123123';
        $command->firstName = 'firstName';
        $command->lastName = 'lastName';
        $command->country = 'FR';
        $command->locale = 'fr';
        $command->multiLogin = false;

        // Mock
        $this->userRepository->findByPhoneNumber('+123123123')->shouldBeCalled()->willReturn(null);
        $this->userRepository->add($expected)->shouldBeCalled();
        $this->generator->generateUuid()->shouldBeCalled()->willReturn('uuid-1');
        $this->tokenGenerator->generateToken()->shouldBeCalled()->willReturn('2oKenN');
        $this->userRepository->findByApiToken('2oKenN')->shouldBeCalled()->willReturn(null);
        $this->localeHelper->country('FR')->shouldBeCalled()->willReturn('France');
        $this->sizeCalculator
            ->calculateSize('uuid-1firstNamelastName+123123123FranceFR')
            ->shouldBeCalled()
            ->willReturn(39)
        ;

        // Handler
        $handler = new CreateHandler(
            $this->userRepository->reveal(),
            $this->generator->reveal(),
            $this->tokenGenerator->reveal(),
            $this->sizeCalculator->reveal(),
            $this->localeHelper->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }
}
