<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Model\Upload;

class File
{
    /** @var int */
    private $id;

    /** @var string */
    private $path;

    /** @var \DateTimeInterface */
    private $createdAt;

    /**
     * @param string             $path
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(string $path, \DateTimeInterface $createdAt)
    {
        $this->path = $path;
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
    public function getPath(): string
    {
        return $this->path;
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
    public function getHash()
    {
        return hash('sha256', $this->getPath() . $this->getCreatedAt()->format('YmdHis') . $this->getId());
    }
}
