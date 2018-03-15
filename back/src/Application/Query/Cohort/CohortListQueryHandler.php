<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:34
 */

namespace App\Application\Query\Cohort;


use App\Application\View\Cohort\CohortView;
use App\Domain\Repository\CohortRepositoryInterface;

class CohortListQueryHandler {
    /** @var CohortRepositoryInterface */
    public $cohortRepository;

    /**
     * CohortListQueryHandler constructor.
     * @param CohortRepositoryInterface $cohortRepository
     */
    public function __construct(CohortRepositoryInterface $cohortRepository) {
        $this->cohortRepository = $cohortRepository;
    }

    public function handle(CohortListQuery $query): array {
        $cohortViews = [];
        $cohorts = $this->cohortRepository->findByInstitution($query->institution);

        foreach($cohorts as $cohort) {
            $cohortViews[] = new CohortView($cohort->getId(), $cohort->getTitle());
        }

        return $cohortViews;
    }
}