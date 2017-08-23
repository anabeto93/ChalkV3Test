<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model;

use App\Domain\Model\Session\File;
use Doctrine\Common\Collections\ArrayCollection;

class Session
{
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var int */
    private $rank;

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

    /** @var \DateTimeInterface */
    private $contentUpdatedAt;

    /** @var int */
    private $size;

    /** @var int */
    private $contentSize;

    /** @var ArrayCollection of File */
    private $files;

    /**
     * @param string             $uuid
     * @param int                $rank
     * @param string             $title
     * @param string|null        $content
     * @param Course             $course
     * @param Folder|null        $folder
     * @param \DateTimeInterface $createdAt
     * @param int                $size
     * @param int                $contentSize
     */
    public function __construct(
        string $uuid,
        int $rank = 0,
        string $title,
        string $content = null,
        Course $course,
        Folder $folder = null,
        \DateTimeInterface $createdAt,
        int $size = 0,
        int $contentSize = 0
    ) {
        $this->uuid = $uuid;
        $this->rank = $rank;
        $this->title = $title;
        $this->content = $content;
        $this->course = $course;
        $this->folder = $folder;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->contentUpdatedAt = $createdAt;
        $this->size = $size;
        $this->contentSize = $contentSize;
        $this->files = new ArrayCollection();
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
     * @param Folder $folder
     */
    public function setFolder(Folder $folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return bool
     */
    public function hasFolder(): bool
    {
        return null !== $this->folder;
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
     * @return \DateTimeInterface
     */
    public function getContentUpdatedAt(): \DateTimeInterface
    {
        return $this->contentUpdatedAt;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getContentSize(): int
    {
        return $this->contentSize;
    }

    /**
     * @param int $size
     */
    public function setContentSize(int $size)
    {
        $this->contentSize = $size;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files->toArray();
    }

    /**
     * @param File[] $files
     */
    public function setFiles(array $files)
    {
        $this->files = new ArrayCollection($files);
    }
}
