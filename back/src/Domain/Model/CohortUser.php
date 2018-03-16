<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 15:39
 */

namespace App\Domain\Model;


class CohortUser {
    /** @var Cohort */
    private $cohort;

    /** @var User */
    private $user;

    /** @var \DateTimeInterface */
    private $assignedAt;

    /**
     * CohortUser constructor.
     * @param Cohort $cohort
     * @param User $user
     * @param \DateTimeInterface $assignedAt
     */
    public function __construct(Cohort $cohort, User $user, \DateTimeInterface $assignedAt) {
        $this->cohort = $cohort;
        $this->user = $user;
        $this->assignedAt = $assignedAt;
    }

    /**
     * @return Cohort
     */
    public function getCohort(): Cohort {
        return $this->cohort;
    }

    /**
     * @return User
     */
    public function getUser(): User {
        return $this->user;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getAssignedAt(): \DateTimeInterface {
        return $this->assignedAt;
    }
}