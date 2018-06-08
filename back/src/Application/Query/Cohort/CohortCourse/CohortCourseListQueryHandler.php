<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 17:52
 */

namespace App\Application\Query\Cohort\CohortCourse;


use App\Application\View\Course\CourseView;
use App\Domain\Repository\CohortCourseRepositoryInterface;
use App\Domain\Repository\CourseRepositoryInterface;

class CohortCourseListQueryHandler {
    /** @var CohortCourseRepositoryInterface */
    private $cohortCourseRepository;

    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /**
     * CohortCourseListQueryHandler constructor.
     * @param CohortCourseRepositoryInterface $cohortCourseRepository
     * @param CourseRepositoryInterface $courseRepository
     */
    public function __construct(CohortCourseRepositoryInterface $cohortCourseRepository, CourseRepositoryInterface $courseRepository)
    {
        $this->cohortCourseRepository = $cohortCourseRepository;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @param CohortCourseListQuery $query
     * @return CourseView[]
     */
    public function handle(CohortCourseListQuery $query): array {
        $cohortCourses = $this->cohortCourseRepository->findByCohort($query->cohort);

        $courseViews = [];

        foreach ($cohortCourses as $cohortCourse) {
            $course = $cohortCourse->getCourse();

            $courseViews[] = new CourseView(
                $course->getId(),
                $course->getTitle(),
                $course->getTeacherName(),
                $course->isEnabled(),
                $this->courseRepository->countFoldersForCourse($course),
                $this->courseRepository->countSessionsForCourse($course),
                $this->courseRepository->countUsersForCourse($course)
            );
        }

        return $courseViews;
    }
}