<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Session\Progression;

class UserValidatedView extends UserView
{
    /** @var \DateTimeInterface */
    public $validatedAt;

    /**
     * @param string             $lastName
     * @param string             $firstName
     * @param string             $phoneNumber
     * @param \DateTimeInterface $validatedAt
     */
    public function __construct(
        string $lastName,
        string $firstName,
        string $phoneNumber,
        \DateTimeInterface $validatedAt
    ) {
        parent::__construct($lastName, $firstName, $phoneNumber);

        $this->validatedAt = $validatedAt;
    }
}
