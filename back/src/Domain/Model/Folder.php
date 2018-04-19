<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model;

class Folder
{
    const DEFAULT_FOLDER = 'default';

    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var int */
    private $rank;

    /** @var Course */
    private $course;

    /** @var string */
    private $title;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /** @var int */
    private $size;

    /**
     * @param string             $uuid
     * @param int                $rank
     * @param string             $title
     * @param Course             $course
     * @param \DateTimeInterface $createdAt
     * @param int                $size
     */
    public function __construct(
        string $uuid,
        int $rank = 0,
        string $title,
        Course $course,
        \DateTimeInterface $createdAt,
        int $size = 0
    ) {
        $this->uuid = $uuid;
        $this->rank = $rank;
        $this->title = $title;
        $this->course = $course;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->size = $size;
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
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
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
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int                $rank
     * @param string             $title
     * @param int                $size
     * @param \DateTimeInterface $updatedAt
     */
    public function update(int $rank, string $title, int $size, \DateTimeInterface $updatedAt)
    {
        $this->rank = $rank;
        $this->title = $title;
        $this->size = $size;
        $this->updatedAt = $updatedAt;
    }
}
