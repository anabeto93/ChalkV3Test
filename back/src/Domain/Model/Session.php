<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model;

class Session
{
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var string */
    private $title;

    /** @var string|null */
    private $content;

    /** @var Course */
    private $course;

    /** @var Folder|null */
    private $folder;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /**
     * @param string             $uuid
     * @param string             $title
     * @param string|null        $content
     * @param Course             $course
     * @param Folder|null        $folder
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(
        string $uuid,
        string $title,
        string $content = null,
        Course $course,
        Folder $folder = null,
        \DateTimeInterface $createdAt
    ) {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->content = $content;
        $this->course = $course;
        $this->folder = $folder;
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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return Folder|null
     */
    public function getFolder(): ?Folder
    {
        return $this->folder;
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
