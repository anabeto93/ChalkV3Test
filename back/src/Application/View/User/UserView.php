<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\User;

class UserView
{
    /** @var int */
    public $id;

    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /** @var string */
    public $phoneNumber;

    /** @var string */
    public $country;

    /** @var string */
    public $apiToken;

    /** @var \DateTimeInterface */
    public $createdAt;

    /** @var \DateTimeInterface|null */
    public $lastLoginAccessNotificationAt;

    /** @var bool */
    public $multiLogin;

    /**
     * @param int                     $id
     * @param string                  $firstName
     * @param string                  $lastName
     * @param string                  $phoneNumber
     * @param string                  $country
     * @param string                  $apiToken
     * @param \DateTimeInterface      $createdAt
     * @param \DateTimeInterface|null $lastLoginAccessNotificationAt
     * @param bool                    $multiLogin
     */
    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $country,
        string $apiToken,
        \DateTimeInterface $createdAt,
        ?\DateTimeInterface $lastLoginAccessNotificationAt = null,
        bool $multiLogin
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->country = $country;
        $this->apiToken = $apiToken;
        $this->createdAt = $createdAt;
        $this->lastLoginAccessNotificationAt = $lastLoginAccessNotificationAt;
        $this->multiLogin = $multiLogin;
    }
}
