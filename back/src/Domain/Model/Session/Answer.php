<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model\Session;

class Answer
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var bool */
    private $correct;

    /** @var Question */
    private $question;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /**
     * @param string             $title
     * @param bool               $correct
     * @param Question           $question
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(string $title, bool $correct, Question $question, \DateTimeInterface $createdAt)
    {
        $this->title     = $title;
        $this->correct   = $correct;
        $this->question  = $question;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
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
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->correct;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
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
}
