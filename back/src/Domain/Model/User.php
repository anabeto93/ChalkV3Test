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

class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $phoneNumber;

    /** @var string ISO 3166-1 alpha-2 country code */
    private $country;

    /** @var string|null */
    private $apiToken;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /** @var int */
    private $size;

    /** @var ArrayCollection */
    private $courses;

    /** @var string */
    private $locale;

    /**
     * @param string             $uuid
     * @param string             $firstName
     * @param string             $lastName
     * @param string             $phoneNumber
     * @param string             $country
     * @param int                $size
     * @param string             $locale
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(
        string $uuid,
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $country,
        string $locale,
        int $size,
        \DateTimeInterface $createdAt
    ) {
        $this->uuid = $uuid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
        $this->country = $country;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->size = $size;
        $this->apiToken = null;
        $this->courses = new ArrayCollection();
        $this->locale = $locale;
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
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
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
     * @return null|string
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     */
    public function setApiToken(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @return Course[]
     */
    public function getCourses(): array
    {
        return $this->courses->toArray();
    }

    /**
     * @return Course[]
     */
    public function getEnabledCourses(): array
    {
        return $this->courses->filter(function (Course $course) {
            return $course->isEnabled();
        })->toArray();
    }

    /**
     * @param array $courses
     */
    public function setCourses(array $courses)
    {
        $this->courses = new ArrayCollection($courses);
    }

    /**
     * @param string             $firstName
     * @param string             $lastName
     * @param string             $country
     * @param string             $locale
     * @param string             $phoneNumber
     * @param int                $size
     * @param \DateTimeInterface $updatedAt
     */
    public function update(
        string $firstName,
        string $lastName,
        string $country,
        string $locale,
        string $phoneNumber,
        int $size,
        \DateTimeInterface $updatedAt
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->country = $country;
        $this->locale = $locale;
        $this->phoneNumber = $phoneNumber;
        $this->size = $size;
        $this->updatedAt = $updatedAt;
    }
}
