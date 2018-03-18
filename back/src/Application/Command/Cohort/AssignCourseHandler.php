<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 19:15
 */

namespace App\Application\Command\Cohort;


use App\Domain\Model\CohortCourse;
use App\Domain\Repository\CohortRepositoryInterface;

class AssignCourseHandler {
    /** @var CohortRepositoryInterface */
    private $cohortRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * AssignCourseHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(CohortRepositoryInterface $cohortRepository, \DateTimeInterface $dateTime) {
        $this->cohortRepository = $cohortRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param AssignCourse $command
     */
    public function handle(AssignCourse $command) {
        $alreadyAssignedCourses = $command->cohort->getCourses();

        foreach ($command->courses as $assignedCourse) {
            if(!in_array($assignedCourse, $alreadyAssignedCourses, true)) {
                $command->cohort->addCohortCourse(new CohortCourse(
                    $command->cohort, $assignedCourse, $this->dateTime
                ));
            }
        }

        foreach ($alreadyAssignedCourses as $alreadyAssignedCourse) {
            if(!in_array($alreadyAssignedCourse, $command->courses, true)) {
                $command->cohort->removeCohortCourse($command->cohort, $alreadyAssignedCourse);
            }
        }

        $this->cohortRepository->set($command->cohort);
    }
}