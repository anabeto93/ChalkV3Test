<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 16:57
 */

namespace App\Domain\Model;


class CohortCourse {
    /** @var Cohort */
    private $cohort;

    /** @var Course */
    private $course;

    /** @var \DateTimeInterface */
    private $assignedAt;

    /**
     * CohortCourse constructor.
     * @param Cohort $cohort
     * @param Course $course
     * @param \DateTimeInterface $assignedAt
     */
    public function __construct(Cohort $cohort, Course $course, \DateTimeInterface $assignedAt) {
        $this->cohort = $cohort;
        $this->course = $course;
        $this->assignedAt = $assignedAt;
    }

    /**
     * @return Cohort
     */
    public function getCohort(): Cohort {
        return $this->cohort;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course {
        return $this->course;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getAssignedAt(): \DateTimeInterface {
        return $this->assignedAt;
    }
}