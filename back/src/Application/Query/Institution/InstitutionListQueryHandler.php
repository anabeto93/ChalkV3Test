<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 28/02/2018
 * Time: 03:05
 */

namespace App\Application\Query\Institution;


use App\Application\View\Institution\InstitutionView;
use App\Domain\Repository\InstitutionRepositoryInterface;
use App\Domain\Repository\UserInstitutionRepositoryInterface;

class InstitutionListQueryHandler {
    /** @var InstitutionRepositoryInterface */
    private $institutionRepository;

    /** @var UserInstitutionRepositoryInterface */
    private $userInstitutionRepository;

    /**
     * InstitutionListQueryHandler constructor.
     * @param InstitutionRepositoryInterface $institutionRepository
     * @param UserInstitutionRepositoryInterface $userInstitutionRepository
     */
    public function __construct(InstitutionRepositoryInterface $institutionRepository, UserInstitutionRepositoryInterface $userInstitutionRepository) {
        $this->institutionRepository = $institutionRepository;
        $this->userInstitutionRepository = $userInstitutionRepository;
    }


    /**
     * @param InstitutionListQuery $institutionListQuery
     * @return array
     */
    public function handle(InstitutionListQuery $institutionListQuery): array {
        $institutionViews = [];
        $institutions = $this->institutionRepository->getAll();

        foreach($institutions as $institution) {
            $institutionViews[] = new InstitutionView(
              $institution->getId(),
              $institution->getName(),
              $this->userInstitutionRepository->countUserForInstitution($institution),
              count($institution->getCohorts())
            );
        }

        return $institutionViews;
    }
}