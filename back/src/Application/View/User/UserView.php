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
    public  $id;

    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /** @var string */
    public $phoneNumber;

    /** @var string */
    public $country;

    /**
     * @param int    $id
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber
     * @param string $country
     */
    public function __construct(int $id, string $firstName, string $lastName, string $phoneNumber, string $country)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->country = $country;
    }
}
