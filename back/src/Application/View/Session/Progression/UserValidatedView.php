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

    /** @var string */
    public $medium;

    /** @var bool */
    public $hasQuizAnswer;

    /** @var null|float */
    public $percentageQuizResult;

    /**
     * @param string             $lastName
     * @param string             $firstName
     * @param string             $phoneNumber
     * @param string             $medium
     * @param \DateTimeInterface $validatedAt
     * @param bool               $hasQuizAnswer
     * @param null|float         $percentageQuizResult
     */
    public function __construct(
        string $lastName,
        string $firstName,
        string $phoneNumber,
        string $medium,
        \DateTimeInterface $validatedAt,
        bool $hasQuizAnswer = false,
        ?float $percentageQuizResult = null
    ) {
        parent::__construct($lastName, $firstName, $phoneNumber);

        $this->validatedAt = $validatedAt;
        $this->medium = $medium;
        $this->hasQuizAnswer = $hasQuizAnswer;
        $this->percentageQuizResult = $percentageQuizResult;
    }
}
