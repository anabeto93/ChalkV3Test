<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/03/2018
 * Time: 12:14
 */

namespace App\Application\Command\UserInstitution;


use App\Domain\Repository\UserInstitutionRepositoryInterface;

class DeleteHandler {
    /** @var UserInstitutionRepositoryInterface */
    private $userInstitutionRepository;

    /**
     * DeleteHandler constructor.
     * @param UserInstitutionRepositoryInterface $userInstitutionRepository
     */
    public function __construct(UserInstitutionRepositoryInterface $userInstitutionRepository) {
        $this->userInstitutionRepository = $userInstitutionRepository;
    }

    /**
     * @param Delete $command
     */
    public function handle(Delete $command) {
        $this->userInstitutionRepository->remove($command->userInstitution);
    }
}