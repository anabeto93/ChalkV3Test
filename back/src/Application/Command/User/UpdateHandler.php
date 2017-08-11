<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Domain\Exception\User\PhoneNumberAlreadyUsedException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Size\Calculator;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class UpdateHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var LocaleHelper */
    private $localeHelper;

    /** @var Calculator */
    private $sizeCalculator;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param LocaleHelper            $localeHelper
     * @param Calculator              $sizeCalculator
     * @param \DateTimeInterface      $dateTime
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        LocaleHelper $localeHelper,
        Calculator $sizeCalculator,
        \DateTimeInterface $dateTime
    ) {
        $this->userRepository = $userRepository;
        $this->dateTime = $dateTime;
        $this->localeHelper = $localeHelper;
        $this->sizeCalculator = $sizeCalculator;
    }

    /**
     * @param Update $command
     *
     * @throws PhoneNumberAlreadyUsedException
     */
    public function handle(Update $command)
    {
        if ($command->user->getPhoneNumber() !== $command->phoneNumber) {
            $userWithSamePhoneNumber = $this->userRepository->findByPhoneNumber($command->phoneNumber);

            if ($userWithSamePhoneNumber !== null) {
                throw new PhoneNumberAlreadyUsedException();
            }
        }

        $command->user->update(
            $command->firstName,
            $command->lastName,
            $command->country,
            $command->phoneNumber,
            $this->sizeCalculator->calculateSize(
                sprintf(
                    '%s%s%s%s%s%s',
                    $command->user->getUuid(),
                    $command->firstName,
                    $command->lastName,
                    $command->phoneNumber,
                    $this->localeHelper->country($command->country),
                    $command->country
                )
            ),
            $this->dateTime
        );

        $this->userRepository->set($command->user);
    }
}
