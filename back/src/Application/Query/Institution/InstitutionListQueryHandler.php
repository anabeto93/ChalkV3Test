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

class InstitutionListQueryHandler {
    /** @var InstitutionRepositoryInterface */
    private $institutionRepository;

    /**
     * InstitutionListQueryHandler constructor.
     * @param InstitutionRepositoryInterface $institutionRepository
     */
    public function __construct(InstitutionRepositoryInterface $institutionRepository) {
        $this->institutionRepository = $institutionRepository;
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
              count($institution->getUsers())
            );
        }

        return $institutionViews;
    }
}