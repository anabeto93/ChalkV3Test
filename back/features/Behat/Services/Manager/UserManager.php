<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Size\Calculator;
use Sonata\IntlBundle\Templating\Helper\LocaleHelper;

class UserManager
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var Calculator */
    private $sizeCalculator;

    /** @var LocaleHelper */
    private $localeHelper;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param Calculator              $sizeCalculator
     * @param LocaleHelper            $localeHelper
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        Calculator $sizeCalculator,
        LocaleHelper $localeHelper
    ) {
        $this->userRepository = $userRepository;
        $this->sizeCalculator = $sizeCalculator;
        $this->localeHelper = $localeHelper;
    }

    /**
     * @param string $uuid
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber
     * @param string $locale
     * @param string $country
     * @param string $token
     * @param bool   $forceUpdate
     *
     * @return User
     */
    public function create(
        string $uuid,
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $locale,
        ?string $country,
        ?string $token,
        bool $forceUpdate = false
    ): User {
        $user = new User(
            $uuid,
            $firstName,
            $lastName,
            $phoneNumber,
            $country ?? 'GH',
            $locale,
            $this->sizeCalculator->calculateSize(
                sprintf(
                    '%s%s%s%s%s%s',
                    $uuid,
                    $firstName,
                    $lastName,
                    $phoneNumber,
                    'GH',
                    $this->localeHelper->country('GH')
                )
            ),
            $token ?? 'api-token-user',
            new \DateTime()
        );

        if ($forceUpdate === true) {
            $user->forceUpdate();
        }

        $this->userRepository->add($user);

        return $user;
    }

    /**
     * @param User   $user
     * @param string $apiToken
     *
     * @return User
     */
    public function setApiToken(User $user, string $apiToken): User
    {
        $user->setApiToken($apiToken);

        $this->userRepository->set($user);

        return $user;
    }
}
