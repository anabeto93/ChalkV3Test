<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Uuid\Generator;

class CreateHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var Generator */
    private $generator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param Generator               $generator
     * @param \DateTimeInterface      $dateTime
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        Generator $generator,
        \DateTimeInterface $dateTime
    ) {
        $this->userRepository = $userRepository;
        $this->generator = $generator;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Create $command
     *
     * @throws PhoneNumberAlreadyUsedException
     */
    public function handle(Create $command)
    {
        $userWithSamePhoneNumber = $this->userRepository->findByPhoneNumber($command->phoneNumber);

        if ($userWithSamePhoneNumber !== null) {
            throw new PhoneNumberAlreadyUsedException();
        }

        $user = new User(
            $this->generator->generateUuid(),
            $command->firstName,
            $command->lastName,
            $command->phoneNumber,
            $command->country,
            $this->dateTime
        );

        $this->userRepository->add($user);
    }
}
