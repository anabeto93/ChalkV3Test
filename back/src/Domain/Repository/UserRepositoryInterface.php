<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Repository;

use App\Domain\Model\User;

interface UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function add(User $user);

    /**
     * @param User $user
     */
    public function set(User $user);

    /**
     * @param string|null $apiToken
     *
     * @return User|null
     */
    public function findByApiToken(string $apiToken = null): ?User;

    /**
     * @param string|null $apiToken
     *
     * @return null|string
     */
    public function findUserNameByApiToken(string $apiToken = null): ?string;

    /**
     * @param string|null $phoneNumber
     *
     * @return User|null
     */
    public function findByPhoneNumber(string $phoneNumber = null): ?User;
}