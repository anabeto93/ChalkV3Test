<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Session\Progression;

class UserView
{
    /** @var string */
    public $lastName;

    /** @var string */
    public $firstName;

    /** @var string */
    public $phoneNumber;

    /**
     * @param string $lastName
     * @param string $firstName
     * @param string $phoneNumber
     */
    public function __construct(
        string $lastName,
        string $firstName,
        string $phoneNumber
    ) {
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->phoneNumber = $phoneNumber;
    }
}
