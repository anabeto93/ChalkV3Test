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
use App\Domain\Size\Calculator;
use App\Domain\Uuid\Generator;
use App\Infrastructure\Service\TokenGenerator;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class CreateHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var Generator */
    private $generator;

    /** @var TokenGenerator */
    private $tokenGenerator;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var Calculator */
    private $sizeCalculator;

    /** @var LocaleHelper */
    private $localeHelper;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param Generator               $generator
     * @param TokenGenerator          $tokenGenerator
     * @param Calculator              $sizeCalculator
     * @param LocaleHelper            $localeHelper
     * @param \DateTimeInterface      $dateTime
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        Generator $generator,
        TokenGenerator $tokenGenerator,
        Calculator $sizeCalculator,
        LocaleHelper $localeHelper,
        \DateTimeInterface $dateTime
    ) {
        $this->userRepository = $userRepository;
        $this->generator = $generator;
        $this->tokenGenerator = $tokenGenerator;
        $this->dateTime = $dateTime;
        $this->sizeCalculator = $sizeCalculator;
        $this->localeHelper = $localeHelper;
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

        $uuid = $this->generator->generateUuid();

        $token = null;

        while (null === $token || null !== $this->userRepository->findByApiToken($token)) {
            $token = $this->tokenGenerator->generateToken();
        }

        $user = new User(
            $uuid,
            $command->firstName,
            $command->lastName,
            $command->phoneNumber,
            $command->country,
            $command->locale,
            $this->sizeCalculator->calculateSize(
                sprintf(
                    '%s%s%s%s%s%s',
                    $uuid,
                    $command->firstName,
                    $command->lastName,
                    $command->phoneNumber,
                    $this->localeHelper->country($command->country),
                    $command->country
                )
            ),
            $token,
            $this->dateTime
        );

        $this->userRepository->add($user);
    }
}
