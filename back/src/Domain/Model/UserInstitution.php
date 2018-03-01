<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 15:44
 */

namespace App\Domain\Model;


class UserInstitution {
    /** @var User */
    private $user;

    /** @var Institution */
    private $institution;

    /** @var \DateTimeInterface */
    private $assignedAt;

    /**
     * UserInstitution constructor.
     * @param User $user
     * @param Institution $institution
     * @param \DateTimeInterface $assignedAt
     */
    public function __construct(User $user, Institution $institution, \DateTimeInterface $assignedAt) {
        $this->user = $user;
        $this->institution = $institution;
        $this->assignedAt = $assignedAt;
    }

    /**
     * @return User
     */
    public function getUser(): User {
        return $this->user;
    }

    /**
     * @return Institution
     */
    public function getInstitution(): Institution {
        return $this->institution;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getAssignedAt(): \DateTimeInterface {
        return $this->assignedAt;
    }
}