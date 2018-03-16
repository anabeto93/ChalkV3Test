<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 17:34
 */

namespace App\Application\Command\Cohort;


use App\Domain\Model\CohortUser;
use App\Domain\Repository\CohortRepositoryInterface;

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


    public function handle(AssignUser $command) {
        $alreadyAssignedUsers = $command->cohort->getUsers();

        foreach ($command->users as $assignedUser) {
            if(!in_array($assignedUser, $alreadyAssignedUsers, true)) {
                $command->cohort->addCohortUser(new CohortUser(
                    $command->cohort, $assignedUser, $this->dateTime
                ));
            }
        }

        foreach ($alreadyAssignedUsers as $alreadyAssignedUser) {
            if(!in_array($alreadyAssignedUser, $command->users, true)) {
                $command->cohort->removeCohortUser($command->cohort, $alreadyAssignedUser);
            }
        }

        $this->cohortRepository->set($command->cohort);
    }
}