<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User;

use App\Domain\Model\User;

class Update extends AbstractUserCommand
{
    /** @var User */
    public $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->phoneNumber = $user->getPhoneNumber();
        $this->country = $user->getCountry();
        $this->locale = $user->getLocale();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
    }
}
