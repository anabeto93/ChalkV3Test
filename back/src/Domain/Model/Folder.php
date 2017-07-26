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
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

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
     * @param string             $title
     * @param Course             $course
     * @param \DateTimeInterface $createdAt
     * @param int                $size
     */
    public function __construct(
        string $uuid,
        string $title,
        Course $course,
        \DateTimeInterface $createdAt,
        int $size = 0
    ) {
        $this->uuid = $uuid;
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
}
