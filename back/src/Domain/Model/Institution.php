<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 27/02/2018
 * Time: 23:24
 */

namespace App\Domain\Model;


use Doctrine\Common\Collections\ArrayCollection;

class Institution {
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var string */
    private $name;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /** @var int */
    private $size;

    /** @var ArrayCollection of Cohort */
    private $cohorts;

    /**
     * Institution constructor.
     * @param string $uuid
     * @param string $name
     * @param \DateTimeInterface $createdAt
     * @param int $size
     */
    public function __construct(string $uuid, string $name, \DateTimeInterface $createdAt, int
    $size = 0) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->size = $size;

        $this->cohorts = new ArrayCollection();
    }

    /**
     * Institution constructor.
     * @param string $name
     * @param \DateTimeInterface $updatedAt
     * @param int $size
     */
    public function update(string $name, \DateTimeInterface $updatedAt, int
    $size) {
        $this->name = $name;
        $this->updatedAt = $updatedAt;
        $this->size = $size;
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
     * @return string
     */
    public function getName(): string {
        return $this->name;
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

    /**
     * @return int
     */
    public function getSize(): int {
        return $this->size;
    }
  
    /**
     * @return Cohort[]
     */
    public function getCohorts(): array {
        return $this->cohorts->toArray();
    }
}