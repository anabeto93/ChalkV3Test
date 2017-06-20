<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model;

class Course
{
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var string */
    private $title;

    /** @var string|null */
    private $description;

    /** @var string */
    private $teacherName;

    /** @var \DateTimeInterface */
    private $createdAt;

    /**
     * @param string             $uuid
     * @param string             $title
     * @param string|null        $teacherName
     * @param \DateTimeInterface $createdAt
     * @param string             $description
     */
    public function __construct(
        string $uuid,
        string $title,
        string $teacherName,
        \DateTimeInterface $createdAt,
        string $description = null
    ) {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->teacherName = $teacherName;
        $this->description = $description;
        $this->createdAt = $createdAt;
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getTeacherName(): string
    {
        return $this->teacherName;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
