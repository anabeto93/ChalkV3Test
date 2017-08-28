<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\User;

use App\Application\Command\User\Update;
use App\Application\Command\User\UpdateHandler;
use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Size\Calculator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class UpdateHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sizeCalculator;

    /** @var ObjectProphecy */
    private $userRepository;

    /** @var \DateTime */
    private $dateTime;

    /** @var ObjectProphecy */
    private $localeHelper;

    public function setUp()
    {
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->sizeCalculator = $this->prophesize(Calculator::class);
        $this->localeHelper = $this->prophesize(LocaleHelper::class);
        $this->dateTime = new \DateTime();
    }

    public function testPhoneNumberAlreadyUsed()
    {
        // Expected
        $this->setExpectedException(PhoneNumberAlreadyUsedException::class);

        // Context
        $userWithSamePhone = $this->prophesize(User::class);
        $user = new User(
            'uuid-1',
            'firstName',
            'lastName',
            '+123123123',
            'FR',
            'fr',
            39,
            $this->dateTime
        );
        $command = new Update($user);
        $command->phoneNumber = '+33987987987';

        // Mock
        $this->userRepository
            ->findByPhoneNumber('+33987987987')
            ->shouldBeCalled()
            ->willReturn($userWithSamePhone->reveal());

        // Handler
        $handler = new UpdateHandler(
            $this->userRepository->reveal(),
            $this->localeHelper->reveal(),
            $this->sizeCalculator->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }

    public function testHandle()
    {
        // Expected
        $expected = new User(
            'uuid-1',
            'other firstname',
            'other last name',
            '+33987987987',
            'GB',
            'fr',
            39,
            new \DateTime('2017-08-01 10:00:00.000')
        );
        $expected->update(
            'other first name',
            'other last name',
            'GB',
            'fr',
            '+33987987987',
            62,
            $this->dateTime
        );

        // Context
        $user = new User(
            'uuid-1',
            'firstName',
            'lastName',
            '+33987987987',
            'FR',
            'fr',
            39,
            new \DateTime('2017-08-01 10:00:00.000')
        );
        $command = new Update($user);
        $command->phoneNumber = '+33987987987';
        $command->firstName = 'other first name';
        $command->lastName = 'other last name';
        $command->country = 'GB';
        $command->locale = 'fr';

        // Mock
        $this->userRepository->findByPhoneNumber(Argument::any())->shouldNotBeCalled();
        $this->userRepository->set($expected)->shouldBeCalled();
        $this->localeHelper->country('GB')->shouldBeCalled()->willReturn('Great Britain');
        $this->sizeCalculator
            ->calculateSize('uuid-1other first nameother last name+33987987987Great BritainGB')
            ->shouldBeCalled()
            ->willReturn(62)
        ;

        // Handler
        $handler = new UpdateHandler(
            $this->userRepository->reveal(),
            $this->localeHelper->reveal(),
            $this->sizeCalculator->reveal(),
            $this->dateTime
        );
        $handler->handle($command);
    }
}
