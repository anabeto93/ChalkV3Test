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

    /** @var ArrayCollection of UserInstitution */
    private $userInstitutions;

    /**
     * Institution constructor.
     * @param int $id
     * @param string $uuid
     * @param string $name
     * @param \DateTimeInterface $createdAt
     * @param \DateTimeInterface $updatedAt
     * @param int $size
     */
    public function __construct(int $id, string $uuid, string $name, \DateTimeInterface $createdAt, \DateTimeInterface $updatedAt, int $size) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->size = $size;

        $this->userInstitutions = new ArrayCollection();
    }

    /**
     * Institution constructor.
     * @param string $uuid
     * @param string $name
     * @param \DateTimeInterface $updatedAt
     * @param int $size
     */
    public function update(string $uuid, string $name, \DateTimeInterface $updatedAt, int
    $size) {
        $this->uuid = $uuid;
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
     * @return UserInstitution[]
     */
    public function getUserInstitutions(): array {
        return $this->userInstitutions->toArray();
    }

    /**
     * @return User[]
     */
    public function getUsers(): array {
        return array_map(
          function(UserInstitution $userInstitution) {
              return $userInstitution->getUser();
          }, $this->userInstitutions->toArray()
        );
    }
}