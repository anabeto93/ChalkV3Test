<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\User\Import;

class UserImportView
{
    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /** @var string */
    public $phoneNumber;

    /** @var string */
    public $country;

    /** @var string */
    public $language;

    /** @var string[] */
    public $errors;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber
     * @param string $country
     * @param string $language
     */
    public function __construct(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $country,
        string $language
    ) {
        $this->firstName   = $firstName;
        $this->lastName    = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->country     = $country;
        $this->language    = $language;
        $this->errors      = [];
    }

    /**
     * @param string $message
     */
    public function addError(string $message)
    {
        $this->errors[] = $message;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->errors);
    }
}
