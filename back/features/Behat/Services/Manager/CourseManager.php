<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\Course;
use App\Domain\Model\User;
use App\Domain\Model\UserCourse;
use App\Domain\Repository\CourseRepositoryInterface;
use Tests\Factory\CourseFactory;

class CourseManager
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param CourseRepositoryInterface $courseRepository
     * @param \DateTimeInterface        $dateTime
     */
    public function __construct(CourseRepositoryInterface $courseRepository, \DateTimeInterface $dateTime)
    {
        $this->courseRepository = $courseRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param string $uuid
     * @param string $title
     *
     * @return Course
     */
    public function create(string $uuid, string $title): Course
    {
        $course = CourseFactory::create($uuid, $title, new \DateTime());
        $this->courseRepository->add($course);

        return $course;
    }

    /**
     * @param string    $uuid
     * @param string    $title
     * @param string    $teacherName
     * @param string    $university
     * @param bool      $enabled
     * @param string    $description
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     * @param int       $size
     *
     * @return Course
     */
    public function createWithAllParameters(
        string $uuid,
        string $title,
        string $teacherName,
        string $university,
        bool $enabled,
        string $description,
        \DateTime $createdAt,
        \DateTime $updatedAt,
        int $size
    ): Course {
        $course = new Course($uuid, $title, $teacherName, $university, $enabled, $createdAt, $description, $size);
        $course->setUpdatedAt($updatedAt);

        $this->courseRepository->add($course);

        return $course;
    }

    /**
     * @param User               $user
     * @param Course             $course
     * @param \DateTimeInterface $dateTime
     */
    public function addCourseToUser(User $user, Course $course, ?\DateTimeInterface $dateTime = null)
    {
        $userCourse = new UserCourse($user, $course, $dateTime ?? $this->dateTime);
        $course->addUserCourse($userCourse);
        $user->addUserCourse($userCourse);
        $this->courseRepository->set($course);
    }
}
