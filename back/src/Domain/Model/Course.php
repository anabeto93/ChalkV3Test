<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;

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

    /** @var string */
    private $university;

    /** @var bool */
    private $enabled;

    /** @var ArrayCollection of Folder */
    private $folders;

    /** @var ArrayCollection of Session */
    private $sessions;

    /** @var ArrayCollection of User */
    private $users;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /** @var int */
    private $size;

    /**
     * @param string             $uuid
     * @param string             $title
     * @param string|null        $teacherName
     * @param string             $university
     * @param bool               $enabled
     * @param \DateTimeInterface $createdAt
     * @param string             $description
     * @param int                $size
     */
    public function __construct(
        string $uuid,
        string $title,
        string $teacherName,
        string $university,
        bool $enabled,
        \DateTimeInterface $createdAt,
        string $description = null,
        int $size = 0
    ) {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->teacherName = $teacherName;
        $this->enabled = $enabled;
        $this->university = $university;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->sessions = new ArrayCollection();
        $this->folders = new ArrayCollection();
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
     * @return string
     */
    public function getUniversity(): string
    {
        return $this->university;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Session[]
     */
    public function getSessions(): array
    {
        return $this->sessions->toArray();
    }

    /**
     * @param Session[] $sessions
     */
    public function setSessions(array $sessions)
    {
        $this->sessions = new ArrayCollection($sessions);
    }

    /**
     * @return Folder[]
     */
    public function getFolders(): array
    {
        return $this->folders->toArray();
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param string             $title
     * @param null|string        $description
     * @param string             $teacherName
     * @param string             $university
     * @param bool               $enabled
     * @param int                $size
     * @param \DateTimeInterface $updatedAt
     */
    public function update(
        string $title,
        ?string $description,
        string $teacherName,
        string $university,
        bool $enabled,
        int $size,
        \DateTimeInterface $updatedAt
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->teacherName = $teacherName;
        $this->university = $university;
        $this->enabled = $enabled;
        $this->size = $size;
        $this->updatedAt = $updatedAt;
    }
}
