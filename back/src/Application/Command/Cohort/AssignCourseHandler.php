<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 19:15
 */

namespace App\Application\Command\Cohort;


use App\Domain\Model\CohortCourse;
use App\Domain\Model\UserCourse;
use App\Domain\Repository\CohortRepositoryInterface;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

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
        $users = $command->cohort->getUsers();



        foreach ($command->courses as $assignedCourse) {
            if(!in_array($assignedCourse, $alreadyAssignedCourses, true)) {
                $command->cohort->addCohortCourse(new CohortCourse(
                    $command->cohort, $assignedCourse, $this->dateTime
                ));


                foreach ($users as $user) {
                    if(!in_array($user, $assignedCourse->getUsers(), true)) {
                        $assignedCourse->addUserCourse(new UserCourse($user, $assignedCourse,
                            $this->dateTime));

                        $user->forceUpdate();
                    }
                }
            }
        }

        foreach ($alreadyAssignedCourses as $alreadyAssignedCourse) {
            if(!in_array($alreadyAssignedCourse, $command->courses, true)) {
                $command->cohort->removeCohortCourse($command->cohort, $alreadyAssignedCourse);


                foreach ($users as $user) {
                    if (in_array($user, $alreadyAssignedCourse->getUsers(), true)) {
                        $alreadyAssignedCourse->removeUserCourse($user, $alreadyAssignedCourse);

                        $user->forceUpdate();
                    }
                }
            }
        }

        $this->cohortRepository->set($command->cohort);
    }
}