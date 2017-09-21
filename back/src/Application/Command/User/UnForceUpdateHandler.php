<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Domain\Repository\UserRepositoryInterface;

class UnForceUpdateHandler
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
     * @param UnForceUpdate $unForceUpdate
     */
    public function handle(UnForceUpdate $unForceUpdate)
    {
        $unForceUpdate->user->unForceUpdate();
        $this->userRepository->set($unForceUpdate->user);
    }
}
