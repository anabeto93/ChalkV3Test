<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Application\Adapter\TranslatorInterface;
use App\Application\Command\Command;
use App\Application\Command\SMS\Send;
use App\Application\Command\SMS\SendHandler;
use App\Application\View\User\UserView;
use App\Domain\Repository\UserRepositoryInterface;
use DateTimeInterface;

class SendLoginAccessHandler implements Command
{
    const TOKEN_URL_REPLACEMENT = '{token}';

    /** @var DateTimeInterface */
    private $dateTime;

    /** @var string */
    private $frontUrl;

    /** @var string */
    private $frontLoginRoute;

    /** @var SendHandler */
    private $sendHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * @param DateTimeInterface       $dateTime
     * @param string                  $frontUrl
     * @param string                  $frontLoginRoute
     * @param SendHandler             $sendHandler
     * @param TranslatorInterface     $translator
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        DateTimeInterface $dateTime,
        string $frontUrl,
        string $frontLoginRoute,
        SendHandler $sendHandler,
        TranslatorInterface $translator,
        UserRepositoryInterface $userRepository
    ) {
        $this->dateTime = $dateTime;
        $this->sendHandler = $sendHandler;
        $this->translator = $translator;
        $this->userRepository = $userRepository;
        $this->frontUrl = $frontUrl;
        $this->frontLoginRoute = $frontLoginRoute;
    }

    /**
     * @param SendLoginAccess $sendLoginAccess
     *
     * @return int number of notified users
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

        $usersNotified = [];

        foreach ($sendLoginAccess->userViews as $userView) {
            $user = $usersIndexedById[$userView->id] ?? null;

            if (null === $user) {
                continue;
            }

            $frontLoginRouteWithToken = str_replace(
                self::TOKEN_URL_REPLACEMENT,
                $user->getApiToken(),
                $this->frontLoginRoute
            );

            $accessLoginLink = $this->frontUrl . $frontLoginRouteWithToken;

            $message = $this->translator->trans(
                'sms.user.loginAccessMessage',
                ['%accessLoginLink%' => $accessLoginLink],
                'sms',
                $user->getLocale()
            );

            $this->sendHandler->handle(new Send($userView->phoneNumber, $message));

            $user->setLastLoginAccessNotificationAt($this->dateTime);
            $this->userRepository->set($user);

            $usersNotified[] = $user;
        }

        return count($usersNotified);
    }
}
