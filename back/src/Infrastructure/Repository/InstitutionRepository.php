<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 28/02/2018
 * Time: 02:38
 */

namespace App\Infrastructure\Repository;


use App\Domain\Model\Institution;
use App\Domain\Repository\InstitutionRepositoryInterface;
use Doctrine\ORM\EntityManager;

class InstitutionRepository implements InstitutionRepositoryInterface {
    /** @var EntityManager */
    private $entityManager;

    /**
     * InstitutionRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     */
    public function add(Institution $institution) {
        $this->entityManager->persist($institution);
        $this->entityManager->flush($institution);
    }

    /**
     * @inheritdoc
     */
    public function set(Institution $institution) {
        $this->entityManager->flush($institution);
    }

    /**
     * @inheritdoc
     */
    public function remove(Institution $institution) {
        $this->entityManager->remove($institution);
        $this->entityManager->flush($institution);
    }

    /**
     * @inheritdoc
     */
    public function getAll(): array {
        return $this->entityManager->getRepository(Institution::class)->findAll();
    }

    /**
     * @inheritdoc
     */
    public function getByUuid(string $uuid): ?Institution {
        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('institution')
                        ->from(Institution::class, 'institution')
                        ->where('institution.uuid = :uuid')
                        ->setParameter('uuid', $uuid);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @inheritdoc
     */
    public function findByName(string $name = null): ?Institution {
        if($name === null) {
            return null;
        }

        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('institution')
                        ->from(Institution::class, 'institution')
                        ->where('institution.name = :name')
                        ->setParameter('name', $name);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}