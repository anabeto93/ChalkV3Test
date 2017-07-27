<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\User;

use App\Application\View\User\UserListView;
use App\Application\View\User\UserView;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;

class UserListQueryHandler
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
     * @param UserListQuery $query
     *
     * @return UserListView
     */
    public function handle(UserListQuery $query): UserListView
    {
        $users = $this->userRepository->paginate($query->page, 50);
        $userListView = new UserListView($users->page, $users->pages, $users->total);

        /** @var User $user */
        foreach ($users as $user) {
            $userListView->addUser(new UserView(
                $user->getId(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPhoneNumber(),
                $user->getCountry()
            ));
        }

        return $userListView;
    }
}