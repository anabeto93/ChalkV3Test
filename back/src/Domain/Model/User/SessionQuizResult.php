<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model\User;

use App\Domain\Model\Session;
use App\Domain\Model\User;

class SessionQuizResult
{
    /** @var User */
    private $user;

    /** @var Session */
    private $session;

    /** @var int */
    private $correctAnswersNumber;

    /** @var int */
    private $questionsNumber;

    /** @var string */
    private $medium;

    /** @var \DateTimeInterface */
    private $createdAt;

    /**
     * @param User               $user
     * @param Session            $session
     * @param string             $medium
     * @param int                $correctAnswersNumber
     * @param int                $questionsNumber
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(
        User $user,
        Session $session,
        string $medium,
        int $correctAnswersNumber,
        int $questionsNumber,
        \DateTimeInterface $createdAt
    ) {
        $this->user                 = $user;
        $this->session              = $session;
        $this->medium               = $medium;
        $this->correctAnswersNumber = $correctAnswersNumber;
        $this->questionsNumber      = $questionsNumber;
        $this->createdAt            = $createdAt;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getMedium(): string
    {
        return $this->medium;
    }

    /**
     * @return int
     */
    public function getCorrectAnswersNumber(): int
    {
        return $this->correctAnswersNumber;
    }

    /**
     * @return int
     */
    public function getQuestionsNumber(): int
    {
        return $this->questionsNumber;
    }
}
