<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Application\Command\Command;
use App\Application\Command\SMS\Send;
use App\Application\Command\SMS\SendHandler;
use App\Application\View\User\UserView;
use App\Domain\Repository\UserRepositoryInterface;
use DateTimeInterface;

class SendLoginAccessHandler implements Command
{
    /** @var DateTimeInterface */
    private $dateTime;

    /** @var SendHandler */
    private $sendHandler;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * @param DateTimeInterface $dateTime
     * @param SendHandler $sendHandler
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        DateTimeInterface $dateTime,
        SendHandler $sendHandler,
        UserRepositoryInterface $userRepository
    ) {
        $this->sendHandler = $sendHandler;
        $this->userRepository = $userRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param SendLoginAccess $sendLoginAccess
     */
    public function handle(SendLoginAccess $sendLoginAccess)
    {
        $usersIndexedById = $this->userRepository->findByIdsIndexedById(
            array_map(
                function (UserView $userView) {
                    return $userView->id;
                },
                $sendLoginAccess->userViews
            )
        );

        foreach ($sendLoginAccess->userViews as $userView) {
            $user = $usersIndexedById[$userView->id] ?? null;

            if (null === $user) {
                continue;
            }

            $this->sendHandler->handle(new Send($userView->phoneNumber, 'hello'));

            $user->setLastLoginAccessNotificationAt($this->dateTime);
            $this->userRepository->set($user);
        }
    }
}
