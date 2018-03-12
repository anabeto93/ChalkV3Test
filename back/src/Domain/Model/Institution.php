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

    /**
     * @param UserInstitution $userInstitution
     */
    public function addUserInstitution(UserInstitution $userInstitution) {
        $this->userInstitutions->add($userInstitution);
    }

    /**
     * @param User $user
     * @param Institution $institution
     * @return UserInstitution|null
     */
    public function getUserInstitution(User $user, Institution $institution): ?UserInstitution {
        /** @var UserInstitution $userInstitution */
        foreach($this->userInstitutions as $userInstitution) {
            if($user->getId() === $userInstitution->getUser()->getId()
            && $institution->getId() === $userInstitution->getInstitution()->getId()) {
                return $userInstitution;
            }
        }

        return null;
    }

    /**
     * @param User $user
     * @param Institution $institution
     */
    public function removeUserInstitution(User $user, Institution $institution) {
        $userInstitution = $this->getUserInstitution($user, $institution);
        if($userInstitution) {
            $this->userInstitutions->removeElement($userInstitution);
        }
    }
}