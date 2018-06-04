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

class CohortCourseListQueryHandler {
    /** @var CohortCourseRepositoryInterface */
    private $cohortCourseRepository;

    /**
     * CohortCourseListQueryHandler constructor.
     * @param CohortCourseRepositoryInterface $cohortCourseRepository
     */
    public function __construct(CohortCourseRepositoryInterface $cohortCourseRepository) {
        $this->cohortCourseRepository = $cohortCourseRepository;
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
                count($course->getFolders()),
                count($course->getSessions()),
                count($course->getUsers())
            );
        }

        return $courseViews;
    }
}