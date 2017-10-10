<?php
/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Domain\Repository\UserRepositoryInterface;

class ForceUpdateHandler
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
     * @param ForceUpdate $update
     */
    public function handle(ForceUpdate $update)
    {
        foreach ($update->users as $user) {
            $user->forceUpdate();
            $this->userRepository->set($user);
        }
    }
}
