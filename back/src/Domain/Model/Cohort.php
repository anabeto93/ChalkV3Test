<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:21
 */

namespace App\Domain\Model;


class Cohort {
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var Institution */
    private $institution;

    /** @var string */
    private $title;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /**
     * Cohort constructor.
     * @param string $uuid
     * @param Institution $institution
     * @param string $title
     * @param \DateTimeInterface $createdAt
     */
    public function __construct(string $uuid, Institution $institution, string $title,
                                \DateTimeInterface $createdAt) {
        $this->uuid = $uuid;
        $this->institution = $institution;
        $this->title = $title;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
    }

    /**
     * @param string $title
     * @param \DateTimeInterface $updatedAt
     */
    public function update(string $title, \DateTimeInterface $updatedAt) {
        $this->title = $title;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string {
        return $this->uuid;
    }

    /**
     * @return Institution
     */
    public function getInstitution(): Institution {
        return $this->institution;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface {
        return $this->updatedAt;
    }
}