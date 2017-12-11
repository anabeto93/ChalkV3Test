<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model\Session;

use App\Domain\Model\Session;
use Doctrine\Common\Collections\ArrayCollection;

class Question
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var Session */
    private $session;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /** @var ArrayCollection */
    private $answers;

    /**
     * @param string             $title
     * @param Session            $session
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(string $title, Session $session, \DateTimeInterface $createdAt)
    {
        $this->title     = $title;
        $this->session   = $session;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->answers   = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
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
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers(): array
    {
        return $this->answers->toArray();
    }

    /**
     * @param Answer $answer
     */
    public function addAnswer(Answer $answer)
    {
        $this->answers->add($answer);
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        $correctAnswersCount = 0;

        foreach ($this->getAnswers() as $answer) {
            if ($answer->isCorrect()) {
                $correctAnswersCount++;
            }
        }

        return $correctAnswersCount > 1;
    }
}
