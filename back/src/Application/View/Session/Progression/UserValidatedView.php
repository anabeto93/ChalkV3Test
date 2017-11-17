<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\View\Session\Progression;

use App\Domain\Model\User\SessionQuizResult;

class UserValidatedView extends UserView
{
    /** @var \DateTimeInterface */
    public $validatedAt;

    /** @var string */
    public $medium;

    /** @var SessionQuizResult|null */
    public $sessionQuizResult;

    /**
     * @param string                 $lastName
     * @param string                 $firstName
     * @param string                 $phoneNumber
     * @param string                 $medium
     * @param \DateTimeInterface     $validatedAt
     * @param SessionQuizResult|null $sessionQuizResult
     */
    public function __construct(
        string $lastName,
        string $firstName,
        string $phoneNumber,
        string $medium,
        \DateTimeInterface $validatedAt,
        ?SessionQuizResult $sessionQuizResult = null
    ) {
        parent::__construct($lastName, $firstName, $phoneNumber);

        $this->validatedAt = $validatedAt;
        $this->medium = $medium;
        $this->sessionQuizResult = $sessionQuizResult;
    }
}
