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
     * @param string|null $apiToken
     *
     * @return User|null
     */
    public function findByApiToken(string $apiToken = null): ?User;
}
