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

class Course
{
    /** @var int */
    private $id;

    /** @var string */
    private $uuid;

    /** @var Institution */
    private $institution;

    /** @var string */
    private $title;

    /** @var string|null */
    private $description;

    /** @var string */
    private $teacherName;

    /** @var \DateTimeInterface */
    private $createdAt;

    /** @var bool */
    private $enabled;

    /** @var ArrayCollection of Folder */
    private $folders;

    /** @var ArrayCollection of Session */
    private $sessions;

    /** @var ArrayCollection of UserCourse */
    private $userCourses;

    /** @var ArrayCollection of CohortCourse */
    private $cohortCourses;

    /** @var \DateTimeInterface */
    private $updatedAt;

    /** @var int */
    private $size;

    /**
     * @param string             $uuid
     * @param Institution        $institution
     * @param string             $title
     * @param string|null        $teacherName
     * @param bool               $enabled
     * @param \DateTimeInterface $createdAt
     * @param string             $description
     * @param int                $size
     */
    public function __construct(
        string $uuid,
        Institution $institution,
        string $title,
        string $teacherName,
        bool $enabled,
        \DateTimeInterface $createdAt,
        string $description = null,
        int $size = 0
    ) {
        $this->uuid = $uuid;
        $this->institution = $institution;
        $this->title = $title;
        $this->teacherName = $teacherName;
        $this->enabled = $enabled;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->size = $size;

        $this->sessions = new ArrayCollection();
        $this->folders = new ArrayCollection();
        $this->userCourses = new ArrayCollection();
        $this->cohortCourses = new ArrayCollection();
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
     * @return Institution
     */
    public function getInstitution(): Institution
    {
        return $this->institution;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getTeacherName(): string
    {
        return $this->teacherName;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Session[]
     */
    public function getSessions(): array
    {
        $sessions = $this->sessions->toArray();

        $this->sortSessionByRank($sessions);

        return $sessions;
    }

    /**
     * @return Session[]
     */
    public function getEnabledSessions(): array
    {
        $sessions = $this->sessions->toArray();

        $enableSessions = array_filter($sessions, function (Session $session) {
            return $session->isEnabled();
        });

        $this->sortSessionByRank($enableSessions);

        return $enableSessions;
    }

    private function sortSessionByRank(array &$sessions)
    {
        usort(
            $sessions,
            function (Session $one, Session $other) {
                return $one->getRank() > $other->getRank();
            }
        );
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return array_map(
            function (UserCourse $userCourse) {
                return $userCourse->getUser();
            },
            $this->userCourses->toArray()
        );
    }

    /**
     * @return UserCourse[]
     */
    public function getUserCourses(): array
    {
        return $this->userCourses->toArray();
    }

    /**
     * @param UserCourse $userCourse
     */
    public function addUserCourse(UserCourse $userCourse)
    {
        $this->userCourses->add($userCourse);
    }

    /**
     * @param User   $user
     * @param Course $course
     *
     * @return null|UserCourse
     */
    public function getUserCourse(User $user, Course $course): ?UserCourse
    {
        /** @var UserCourse $userCourse */
        foreach ($this->userCourses as $userCourse) {
            if ($user->getId() === $userCourse->getUser()->getId()
                && $course->getId() === $userCourse->getCourse()->getId()
            ) {
                return $userCourse;
            }
        }

        return null;
    }

    /**
     * @param User   $user
     * @param Course $course
     */
    public function removeUserCourse(User $user, Course $course)
    {
        $userCourse = $this->getUserCourse($user, $course);

        if (null !== $userCourse) {
            $this->userCourses->removeElement($userCourse);
        }
    }

    /**
     * @param Session[] $sessions
     */
    public function setSessions(array $sessions)
    {
        $this->sessions = new ArrayCollection($sessions);
    }

    /**
     * @return Folder[]
     */
    public function getFolders(): array
    {
        return $this->folders->toArray();
    }


    /**
     * @return Cohort[]
     */
    public function getCohorts(): array {
        return array_map(
            function (CohortCourse $cohortCourse) {
                return $cohortCourse->getCourse();
            },
            $this->cohortCourses->toArray()
        );
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param string             $title
     * @param null|string        $description
     * @param string             $teacherName
     * @param bool               $enabled
     * @param int                $size
     * @param \DateTimeInterface $updatedAt
     */
    public function update(
        string $title,
        ?string $description,
        string $teacherName,
        bool $enabled,
        int $size,
        \DateTimeInterface $updatedAt
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->teacherName = $teacherName;
        $this->enabled = $enabled;
        $this->size = $size;
        $this->updatedAt = $updatedAt;
    }
}
