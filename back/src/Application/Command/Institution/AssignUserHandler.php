<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 02/03/2018
 * Time: 01:00
 */

namespace App\Application\Command\Institution;


use App\Domain\Model\UserInstitution;
use App\Domain\Repository\InstitutionRepositoryInterface;

class AssignUserHandler {
    /** @var InstitutionRepositoryInterface */
    private $institutionRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * AssignUserHandler constructor.
     * @param InstitutionRepositoryInterface $institutionRepository
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(InstitutionRepositoryInterface $institutionRepository, \DateTimeInterface $dateTime) {
        $this->institutionRepository = $institutionRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param AssignUser $assign
     */
    public function handle(AssignUser $assign) {
        $alreadyAssignedUsers = $assign->institution->getUsers();

        foreach($assign->users as $assignedUser) {
            if(!in_array($assignedUser, $alreadyAssignedUsers, true)) {
                $assign->institution->addUserInstitution(new UserInstitution($assignedUser,
                    $assign->institution, $this->dateTime));
            }
        }

        foreach($alreadyAssignedUsers as $alreadyAssignedUser) {
            if(!in_array($alreadyAssignedUser, $assign->users, true)) {
                $assign->institution->removeUserInstitution($alreadyAssignedUser, $assign->institution);
            }
        }

        $this->institutionRepository->set($assign->institution);
    }
}