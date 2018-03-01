<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 15:54
 */

namespace App\Infrastructure\Repository;


use App\Domain\Model\Institution;
use App\Domain\Model\UserInstitution;
use App\Domain\Repository\UserInstitutionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserInstitutionRepository implements UserInstitutionRepositoryInterface {
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * UserInstitutionRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     */
    public function countUserForInstitution(Institution $institution): int {
        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('COUNT(IDENTITY(user_institution))')
                        ->from(UserInstitution::class, 'user_institution')
                        ->where('user_institution.institution = :institution')
                        ->setParameter('institution', $institution);

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @inheritdoc
     */
    public function findByInstitution(Institution $institution): array {
        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('user_institution, user')
                        ->from(UserInstitution::class, 'user_institution')
                        ->join('user_institution.user', 'user', 'WITH', 'user_institution.institution = :institution')
                        ->setParameter('institution', $institution);

        return $queryBuilder->getQuery()->getResult();
    }
}