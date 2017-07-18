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

class UserManager
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $uuid
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber
     *
     * @return User
     */
    public function create(string $uuid, string $firstName, string $lastName, string $phoneNumber): User
    {
        $user = new User($uuid, $firstName, $lastName, $phoneNumber, 'GH', new \DateTime());

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
