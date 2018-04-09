<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:21
 */

namespace App\Domain\Model;


use Doctrine\Common\Collections\ArrayCollection;

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

    /** @var ArrayCollection */
    private $cohortUsers;

    /** @var ArrayCollection */
    private $cohortCourses;

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

        $this->cohortUsers = new ArrayCollection();
        $this->cohortCourses = new ArrayCollection();
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

    /**
     * @return User[]
     */
    public function getUsers(): array {
        return array_map(
            function (CohortUser $cohortUser) {
                return $cohortUser->getUser();
            },
            $this->cohortUsers->toArray()
        );
    }

    /**
     * @param CohortUser $cohortUser
     */
    public function addCohortUser(CohortUser $cohortUser) {
        $this->cohortUsers->add($cohortUser);
    }

    /**
     * @param Cohort $cohort
     * @param User $user
     * @return CohortUser|null
     */
    public function getCohortUser(Cohort $cohort, User $user): ?CohortUser {
        /** @var CohortUser $cohortUser */
        foreach ($this->cohortUsers as $cohortUser) {
            if($cohort->getId() === $cohortUser->getCohort()->getId()
            && $user->getId() === $cohortUser->getUser()->getId()) {
                return $cohortUser;
            }
        }

        return null;
    }

    /**
     * @param Cohort $cohort
     * @param User $user
     */
    public function removeCohortUser(Cohort $cohort, User $user) {
        $cohortUser = $this->getCohortUser($cohort, $user);

        if($cohortUser) {
            $this->cohortUsers->removeElement($cohortUser);
        }
    }

    /**
     * @return Course[]
     */
    public function getCourses(): array {
        return array_map(
            function (CohortCourse $cohortCourse) {
                return $cohortCourse->getCourse();
            },
            $this->cohortCourses->toArray()
        );
    }

    /**
     * @param CohortCourse $cohortCourse
     */
    public function addCohortCourse(CohortCourse $cohortCourse) {
        $this->cohortCourses->add($cohortCourse);
    }

    /**
     * @param Cohort $cohort
     * @param Course $course
     * @return CohortCourse|null
     */
    public function getCohortCourse(Cohort $cohort, Course $course): ?CohortCourse {
        /** @var CohortCourse $cohortCourse */
        foreach ($this->cohortCourses as $cohortCourse) {
            if($cohort->getId() === $cohortCourse->getCohort()->getId()
                && $course->getId() === $cohortCourse->getCourse()->getId()) {
                return $cohortCourse;
            }
        }

        return null;
    }

    /**
     * @param Cohort $cohort
     * @param Course $course
     */
    public function removeCohortCourse(Cohort $cohort, Course $course) {
        $cohortCourse = $this->getCohortCourse($cohort, $course);

        if($cohortCourse) {
            $this->cohortCourses->removeElement($cohortCourse);
        }
    }
}