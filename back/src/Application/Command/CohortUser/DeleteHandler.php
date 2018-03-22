<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 18:46
 */

namespace App\Application\Command\CohortUser;


use App\Domain\Repository\CohortUserRepositoryInterface;

class DeleteHandler {
    /** @var CohortUserRepositoryInterface */
    private $cohortUserRepository;

    /**
     * DeleteHandler constructor.
     * @param CohortUserRepositoryInterface $cohortUserRepository
     */
    public function __construct(CohortUserRepositoryInterface $cohortUserRepository) {
        $this->cohortUserRepository = $cohortUserRepository;
    }

    /**
     * @param Delete $command
     */
    public function handle(Delete $command) {
        $user = $command->cohortUser->getUser();
        $courses = $command->cohortUser->getCohort()->getCourses();

        foreach ($courses as $course) {
            if(in_array($user, $course->getUsers())) {
                $course->removeUserCourse($user, $course);
            }
        }

        $user->forceUpdate();

        $this->cohortUserRepository->remove($command->cohortUser);
    }
}