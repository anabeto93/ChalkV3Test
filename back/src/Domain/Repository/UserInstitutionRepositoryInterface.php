<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 15:50
 */

namespace App\Domain\Repository;


use App\Domain\Model\Institution;
use App\Domain\Model\UserInstitution;

interface UserInstitutionRepositoryInterface {
    /**
     * @param Institution $institution
     * @return int
     */
    public function countUserForInstitution(Institution $institution): int;

    /**
     * @param Institution $institution
     * @return UserInstitution[]
     */
    public function findByInstitution(Institution $institution): array;

    /**
     * @param UserInstitution $userInstitution
     */
    public function remove(UserInstitution $userInstitution);
}