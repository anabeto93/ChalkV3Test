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

class Progression
{
    /** @var User */
    private $user;

    /** @var Session */
    private $session;

    /** @var \DateTimeInterface */
    private $createdAt;

    /**
     * @param User               $user
     * @param Session            $session
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(User $user, Session $session, \DateTimeInterface $createdAt)
    {
        $this->user = $user;
        $this->session = $session;
        $this->createdAt = $createdAt;
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
}
