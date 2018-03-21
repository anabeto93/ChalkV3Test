<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 17:34
 */

namespace App\Application\Command\Cohort;


use App\Domain\Model\CohortUser;
use App\Domain\Model\UserCourse;
use App\Domain\Repository\CohortRepositoryInterface;
use App\Domain\Repository\CourseRepositoryInterface;

class AssignUserHandler {
    /** @var CohortRepositoryInterface */
    private $cohortRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * AssignUserHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(CohortRepositoryInterface $cohortRepository, \DateTimeInterface $dateTime) {
        $this->cohortRepository = $cohortRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param AssignUser $command
     */
    public function handle(AssignUser $command) {
        $alreadyAssignedUsers = $command->cohort->getUsers();
        $courses = $command->cohort->getCourses();

        foreach ($command->users as $assignedUser) {
            if(!in_array($assignedUser, $alreadyAssignedUsers, true)) {
                $command->cohort->addCohortUser(new CohortUser(
                    $command->cohort, $assignedUser, $this->dateTime
                ));


                foreach ($courses as $course) {
                    if (!in_array($assignedUser, $course->getUsers(), true)) {
                        $course->addUserCourse(new UserCourse($assignedUser, $course,
                            $this->dateTime));

                        $assignedUser->forceUpdate();
                    }
                }
            }
        }

        foreach ($alreadyAssignedUsers as $alreadyAssignedUser) {
            if(!in_array($alreadyAssignedUser, $command->users, true)) {
                $command->cohort->removeCohortUser($command->cohort, $alreadyAssignedUser);


                foreach ($courses as $course) {
                    if (in_array($alreadyAssignedUser, $course->getUsers(), true)) {
                        $course->removeUserCourse($alreadyAssignedUser, $course);

                        $alreadyAssignedUser->forceUpdate();
                    }
                }
            }
        }

        $this->cohortRepository->set($command->cohort);
    }
}